<?php

namespace App\Services;

use App\Models\MapCandidate;
use App\Models\TouristService;
use App\Models\TouristSite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MapCandidatePublisher
{
    /**
     * ينقل مرشحًا واحدًا إلى الجدول النهائي مرة واحدة فقط.
     * public=false: محفوظ للمراجعة وغير ظاهر للعامة.
     * public=true: معتمد ونشط ويظهر للعامة، بشرط اكتمال الحقول الأساسية.
     */
    public function publish(int $candidateId, bool $public): Model
    {
        return DB::transaction(function () use ($candidateId, $public) {
            $candidate = MapCandidate::query()->lockForUpdate()->findOrFail($candidateId);
            if ($candidate->status === 'published' || $candidate->published_id) {
                throw ValidationException::withMessages([
                    'duplicate' => 'هذا المرشح نُقل إلى جداول الموقع سابقًا.',
                ]);
            }

            $this->validateForTransfer($candidate, $public);
            $modelClass = $candidate->category === 'service' ? TouristService::class : TouristSite::class;
            $externalId = $this->externalId($candidate);
            $duplicate = $this->findDuplicate($modelClass, $candidate, $externalId);
            if ($duplicate) {
                throw ValidationException::withMessages([
                    'duplicate' => "يوجد سجل مطابق في الموقع مسبقًا (رقم {$duplicate->id})، لذلك تم إيقاف التكرار.",
                ]);
            }

            $governorateId = $candidate->governorate_id ?: DB::table('wilayats')
                ->where('id', $candidate->wilayat_id)
                ->value('governorate_id');
            // يعمل من web ومن API؛ لا نعتمد على session لأن مسارات API
            // لا تشغّل StartSession. معرّف مشرف تلجرام موجود في المرشح.
            $reviewedBy = $candidate->telegram_admin_id ?: 'dashboard-admin';
            $base = [
                'name_ar' => $candidate->name_ar,
                'name_en' => $candidate->name_en ?: $candidate->name_ar,
                'description_ar' => $candidate->description_ar ?: '',
                'description_en' => $candidate->description_en ?: '',
                'governorate_id' => $governorateId,
                'wilayat_id' => $candidate->wilayat_id,
                'source_url' => $this->sourceUrl($candidate),
                'source_name' => $this->sourceName($candidate),
                'source_type' => $this->sourceType($candidate),
                'external_id' => $externalId,
                'latitude' => $candidate->latitude,
                'longitude' => $candidate->longitude,
                'verification_status' => $public ? 'approved' : 'needs_review',
                'needs_review_fields' => $candidate->missing_fields ?: null,
                'last_verified_at' => $public ? now() : null,
                'ai_generated' => true,
                'confidence_score' => $candidate->overall_confidence,
                'source_trust_score' => $candidate->overall_confidence,
                'ai_confidence' => $candidate->overall_confidence,
                'name_ar_verified' => $public,
                'description_ar_generated' => true,
                'coordinates_source' => $this->coordinatesSource($candidate),
                'is_tourism_candidate' => true,
                'collected_at' => $candidate->created_at,
                'source_last_checked_at' => now(),
                'collector_name' => $candidate->osm_type === 'import'
                    ? 'badyah_import_bot'
                    : 'badyah_telegram_bot',
                'collector_version' => $candidate->osm_type === 'import'
                    ? 'xlsx-import-v1'
                    : 'review-page-v1',
                'is_active' => $public,
                'reviewed_by' => $reviewedBy,
                'reviewed_at' => now(),
            ];

            if ($candidate->category === 'service') {
                $finalRecord = TouristService::create($base + [
                    'website_url' => $candidate->website,
                    'image_url' => $candidate->image_is_placeholder ? null : $candidate->image_url,
                    'service_type_id' => $candidate->service_type_id,
                    'phone' => $candidate->phone,
                ]);
                // الحقلان جديدان وقد لا يكونان ضمن $fillable في نسخ الموديل
                // القديمة؛ نحدّثهما مباشرة بعد الإنشاء لتفادي إسقاط البيانات.
                DB::table('tourist_services')->where('id', $finalRecord->id)->update([
                    'address_ar' => $candidate->address_ar,
                    'opening_hours' => $candidate->opening_hours,
                ]);
                $finalRecord->refresh();
                $table = 'tourist_services';
            } else {
                $wilayatName = DB::table('wilayats')->where('id', $candidate->wilayat_id)->value('name_ar');
                $finalRecord = TouristSite::create($base + [
                    'location' => $wilayatName,
                    'website_url' => $candidate->website,
                    'featured_image' => $candidate->image_is_placeholder ? null : $candidate->image_url,
                    'tourist_site_category_id' => $candidate->tourist_site_category_id,
                ]);
                $table = 'tourist_sites';
            }

            $candidate->update([
                'status' => 'published',
                'published_table' => $table,
                'published_id' => $finalRecord->id,
                'published_at' => now(),
            ]);

            return $finalRecord;
        });
    }

    private function validateForTransfer(MapCandidate $candidate, bool $public): void
    {
        $missing = [];
        foreach (['name_ar', 'latitude', 'longitude', 'wilayat_id'] as $field) {
            if (blank($candidate->{$field})) {
                $missing[] = $field;
            }
        }
        if ($public) {
            foreach (['name_en', 'description_ar', 'description_en'] as $field) {
                if (blank($candidate->{$field})) {
                    $missing[] = $field;
                }
            }
        }
        if ($missing) {
            throw ValidationException::withMessages([
                'missing' => 'الحقول المطلوبة غير مكتملة: '.implode(', ', array_unique($missing)),
            ]);
        }
    }

    private function findDuplicate(string $modelClass, MapCandidate $candidate, ?string $externalId): ?Model
    {
        if ($externalId) {
            $legacyIds = array_values(array_unique(array_filter([$externalId, $candidate->osm_id])));
            if ($match = $modelClass::query()->whereIn('external_id', $legacyIds)->first()) {
                return $match;
            }
        }

        return $modelClass::query()
            ->where('wilayat_id', $candidate->wilayat_id)
            ->where(function ($builder) use ($candidate) {
                $builder->where('name_ar', $candidate->name_ar)
                    ->orWhere('name_en', $candidate->name_en);
            })
            ->first();
    }

    private function externalId(MapCandidate $candidate): ?string
    {
        if ($candidate->osm_type === 'import' && $candidate->osm_id) {
            return 'import:'.$candidate->osm_id;
        }

        return ($candidate->osm_type && $candidate->osm_id)
            ? 'osm:'.$candidate->osm_type.':'.$candidate->osm_id
            : null;
    }

    private function sourceUrl(MapCandidate $candidate): ?string
    {
        foreach ($candidate->sources ?: [] as $source) {
            if (!empty($source['url'])) {
                return $source['url'];
            }
        }

        return ($candidate->osm_type && $candidate->osm_id)
            ? "https://www.openstreetmap.org/{$candidate->osm_type}/{$candidate->osm_id}"
            : null;
    }

    private function sourceName(MapCandidate $candidate): string
    {
        if ($candidate->osm_type === 'import') {
            return 'Badyah Telegram Import';
        }

        return 'Badyah Telegram Bot / OpenStreetMap';
    }

    private function sourceType(MapCandidate $candidate): string
    {
        return $candidate->osm_type === 'import' ? 'import_xlsx' : 'osm';
    }

    private function coordinatesSource(MapCandidate $candidate): ?string
    {
        foreach ($candidate->sources ?: [] as $source) {
            if (($source['field'] ?? null) === 'coordinates' && !empty($source['type'])) {
                return (string) $source['type'];
            }
        }

        return $candidate->latitude !== null && $candidate->longitude !== null
            ? ($candidate->osm_type === 'import' ? 'import_file' : 'openstreetmap')
            : null;
    }
}
