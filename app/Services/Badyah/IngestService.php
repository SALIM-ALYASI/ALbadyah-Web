<?php

namespace App\Services\Badyah;

use App\Models\DataSource;
use App\Models\ImportJob;
use App\Models\TouristService;
use App\Models\TouristServiceImage;
use App\Models\TouristSite;
use App\Models\TouristSiteCategory;
use App\Models\VerificationLog;
use Illuminate\Support\Facades\Validator;

/**
 * منطق استقبال بيانات محرك البادية الذكي: تحقق، إزالة تكرار، تتبع مصدر،
 * حفظ دائمًا كـ Pending. مستقل تمامًا عن أي منظومة أخرى غير البادية.
 *
 * قاعدة صارمة: لا يوجد أي مسار في هذا الملف ينشر سجلًا مباشرة للعامة.
 * كل ما يدخل من هنا verification_status = pending فقط.
 */
class IngestService
{
    private const MIN_LAT = 16.0;
    private const MAX_LAT = 27.0;
    private const MIN_LNG = 51.0;
    private const MAX_LNG = 61.0;

    /**
     * @return array{status: string, id: ?int, reasons: array}
     */
    public function ingestTouristSite(array $data, ImportJob $job): array
    {
        $validator = Validator::make($data, [
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'governorate_id' => ['required', 'integer', 'exists:governorates,id'],
            // الولاية اختيارية: بعض المصادر (مثل Experience Oman) تعطي المحافظة فقط
            // بدون تفصيل الولاية. أي سجل بلا ولاية يُعلَّم needs_review تلقائيًا.
            'wilayat_id' => ['nullable', 'integer', 'exists:wilayats,id'],
            'tourist_site_category_id' => ['nullable', 'integer', 'exists:tourist_site_categories,id'],
            'category' => ['nullable', 'array'],
            'category.slug' => ['required_with:category', 'string', 'max:255'],
            'category.name_ar' => ['required_with:category', 'string', 'max:255'],
            'category.name_en' => ['required_with:category', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'source_url' => ['nullable', 'url', 'max:2048'],
            'source_name' => ['nullable', 'string', 'max:255'],
            'source_type' => ['nullable', 'string', 'max:100'],
            'external_id' => ['nullable', 'string', 'max:255'],
            'data_source' => ['nullable', 'array'],
            'data_source.slug' => ['required_with:data_source', 'string', 'max:255'],
            'data_source.name' => ['required_with:data_source', 'string', 'max:255'],
            'data_source.type' => ['nullable', 'string', 'max:100'],
            'data_source.base_url' => ['nullable', 'url', 'max:255'],
            'data_source.trust_level' => ['nullable', 'integer', 'min:1', 'max:5'],
            'ai_generated' => ['nullable', 'boolean'],
            'confidence_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            // source_trust_score: "المصدر رسمي وموثوق" فقط — لا يعني أن كل حقل
            // بالسجل صحيح 100%. ai_confidence: ثقة الذكاء الاصطناعي بعمله هو
            // (ترجمة/تصنيف) فقط. لا تُخلَط الاثنتان في رقم واحد أبدًا.
            'source_trust_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'ai_confidence' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'description_ar_generated' => ['nullable', 'boolean'],
            'name_ar_source' => ['nullable', 'in:official,ai_translation,untranslated'],
            'coordinates_source' => ['nullable', 'string', 'max:255'],
            'is_tourism_candidate' => ['nullable', 'boolean'],
            'excluded_reason' => ['nullable', 'string', 'max:255'],
            'collected_at' => ['nullable', 'date'],
            'source_last_checked_at' => ['nullable', 'date'],
            'collector_name' => ['nullable', 'string', 'max:255'],
            'collector_version' => ['nullable', 'string', 'max:50'],
            'needs_review_fields' => ['nullable', 'array'],
            'images' => ['nullable', 'array'],
            'images.*.url' => ['required_with:images', 'url'],
        ]);

        if ($validator->fails()) {
            return ['status' => 'rejected', 'id' => null, 'reasons' => $validator->errors()->all()];
        }

        $payload = $validator->validated();

        $sourceCheck = $this->requireSource($payload);
        if ($sourceCheck !== null) {
            return ['status' => 'rejected', 'id' => null, 'reasons' => [$sourceCheck]];
        }

        $dataSource = $this->resolveDataSource($payload['data_source'] ?? null);
        $category = $this->resolveSiteCategory($payload['category'] ?? null);

        // 1) تطابق دقيق عبر المصدر + المعرف الخارجي
        if ($dataSource && !empty($payload['external_id'])) {
            $existing = TouristSite::where('data_source_id', $dataSource->id)
                ->where('external_id', $payload['external_id'])
                ->first();

            if ($existing) {
                return $this->handleExistingSite($existing, $payload, $job, $dataSource);
            }
        }

        // 2) تطابق تقريبي بالاسم + المحافظة (والولاية إن وُجدت) لمنع التكرار عند غياب مصدر خارجي موثوق
        $normalizedName = $this->normalizeName($payload['name_ar']);
        $duplicateQuery = TouristSite::where('governorate_id', $payload['governorate_id']);
        if (!empty($payload['wilayat_id'])) {
            $duplicateQuery->where('wilayat_id', $payload['wilayat_id']);
        }
        $possibleDuplicate = $duplicateQuery->get()
            ->first(fn (TouristSite $site) => $this->normalizeName($site->name_ar) === $normalizedName);

        if ($possibleDuplicate) {
            $job->increment('total_duplicates');
            return ['status' => 'duplicate', 'id' => $possibleDuplicate->id, 'reasons' => ['يوجد سجل بنفس الاسم في نفس المحافظة بالفعل.']];
        }

        $needsReviewFields = $this->detectNeedsReview($payload);

        $site = TouristSite::create([
            'name_ar' => $payload['name_ar'],
            'name_en' => $payload['name_en'],
            'description_ar' => $payload['description_ar'] ?? '',
            'description_en' => $payload['description_en'] ?? '',
            'location' => $payload['location'] ?? null,
            'website_url' => $payload['website_url'] ?? null,
            'governorate_id' => $payload['governorate_id'],
            'wilayat_id' => $payload['wilayat_id'] ?? null,
            'tourist_site_category_id' => $payload['tourist_site_category_id'] ?? $category?->id,
            'latitude' => $payload['latitude'] ?? null,
            'longitude' => $payload['longitude'] ?? null,
            'data_source_id' => $dataSource?->id,
            'source_url' => $payload['source_url'] ?? null,
            'source_name' => $payload['source_name'] ?? $dataSource?->name,
            'source_type' => $payload['source_type'] ?? $dataSource?->type,
            'external_id' => $payload['external_id'] ?? null,
            'verification_status' => 'pending', // ثابت دائمًا لأي سجل يدخل عبر البوت
            'needs_review_fields' => $needsReviewFields ?: null,
            'ai_generated' => $payload['ai_generated'] ?? true,
            // confidence_score يبقى للتوافق مع الكود القديم = نفس ai_confidence
            'confidence_score' => $payload['ai_confidence'] ?? $payload['confidence_score'] ?? null,
            'source_trust_score' => $payload['source_trust_score'] ?? null,
            'ai_confidence' => $payload['ai_confidence'] ?? null,
            // لا يُقبل name_ar_verified من الـ payload إطلاقًا: يبقى false دائمًا
            // عند الإدخال، ولا يتحول true إلا عبر قرار اعتماد بشري صريح لاحقًا.
            'name_ar_verified' => false,
            'description_ar_generated' => $payload['description_ar_generated'] ?? true,
            'name_ar_source' => $payload['name_ar_source'] ?? null,
            'coordinates_source' => $payload['coordinates_source'] ?? null,
            'is_tourism_candidate' => $payload['is_tourism_candidate'] ?? true,
            'excluded_reason' => $payload['excluded_reason'] ?? null,
            'collected_at' => $payload['collected_at'] ?? now(),
            'source_last_checked_at' => $payload['source_last_checked_at'] ?? now(),
            'collector_name' => $payload['collector_name'] ?? null,
            'collector_version' => $payload['collector_version'] ?? null,
            'is_active' => true,
        ]);

        $this->attachImages($site, $payload['images'] ?? []);

        VerificationLog::record(
            record: $site,
            action: 'created',
            oldStatus: null,
            newStatus: 'pending',
            actorType: ($payload['ai_generated'] ?? true) ? 'ai' : 'system',
            actorName: $job->workflow_name,
            importJobId: $job->id,
        );

        $job->increment('total_created');

        return ['status' => 'created', 'id' => $site->id, 'reasons' => []];
    }

    /**
     * @return array{status: string, id: ?int, reasons: array}
     */
    public function ingestTouristService(array $data, ImportJob $job): array
    {
        $validator = Validator::make($data, [
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'governorate_id' => ['required', 'integer', 'exists:governorates,id'],
            'wilayat_id' => ['required', 'integer', 'exists:wilayats,id'],
            'service_type_id' => ['required', 'integer', 'exists:service_types,id'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'source_url' => ['nullable', 'url', 'max:2048'],
            'source_name' => ['nullable', 'string', 'max:255'],
            'source_type' => ['nullable', 'string', 'max:100'],
            'external_id' => ['nullable', 'string', 'max:255'],
            'data_source' => ['nullable', 'array'],
            'data_source.slug' => ['required_with:data_source', 'string', 'max:255'],
            'data_source.name' => ['required_with:data_source', 'string', 'max:255'],
            'data_source.type' => ['nullable', 'string', 'max:100'],
            'data_source.base_url' => ['nullable', 'url', 'max:255'],
            'data_source.trust_level' => ['nullable', 'integer', 'min:1', 'max:5'],
            'ai_generated' => ['nullable', 'boolean'],
            'confidence_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'needs_review_fields' => ['nullable', 'array'],
            'images' => ['nullable', 'array'],
            'images.*.url' => ['required_with:images', 'url'],
        ]);

        if ($validator->fails()) {
            return ['status' => 'rejected', 'id' => null, 'reasons' => $validator->errors()->all()];
        }

        $payload = $validator->validated();

        $sourceCheck = $this->requireSource($payload);
        if ($sourceCheck !== null) {
            return ['status' => 'rejected', 'id' => null, 'reasons' => [$sourceCheck]];
        }

        $dataSource = $this->resolveDataSource($payload['data_source'] ?? null);

        if ($dataSource && !empty($payload['external_id'])) {
            $existing = TouristService::where('data_source_id', $dataSource->id)
                ->where('external_id', $payload['external_id'])
                ->first();

            if ($existing) {
                return $this->handleExistingService($existing, $payload, $job, $dataSource);
            }
        }

        $normalizedName = $this->normalizeName($payload['name_ar']);
        $possibleDuplicate = TouristService::where('governorate_id', $payload['governorate_id'])
            ->where('wilayat_id', $payload['wilayat_id'])
            ->where('service_type_id', $payload['service_type_id'])
            ->get()
            ->first(fn (TouristService $service) => $this->normalizeName($service->name_ar) === $normalizedName);

        if ($possibleDuplicate) {
            $job->increment('total_duplicates');
            return ['status' => 'duplicate', 'id' => $possibleDuplicate->id, 'reasons' => ['يوجد سجل بنفس الاسم ونفس نوع الخدمة في نفس الولاية بالفعل.']];
        }

        $needsReviewFields = $this->detectNeedsReview($payload);

        $service = TouristService::create([
            'name_ar' => $payload['name_ar'],
            'name_en' => $payload['name_en'],
            'description_ar' => $payload['description_ar'] ?? null,
            'description_en' => $payload['description_en'] ?? null,
            'website_url' => $payload['website_url'] ?? null,
            'phone' => $payload['phone'] ?? null,
            'whatsapp' => $payload['whatsapp'] ?? null,
            'governorate_id' => $payload['governorate_id'],
            'wilayat_id' => $payload['wilayat_id'],
            'service_type_id' => $payload['service_type_id'],
            'latitude' => $payload['latitude'] ?? null,
            'longitude' => $payload['longitude'] ?? null,
            'data_source_id' => $dataSource?->id,
            'source_url' => $payload['source_url'] ?? null,
            'source_name' => $payload['source_name'] ?? $dataSource?->name,
            'source_type' => $payload['source_type'] ?? $dataSource?->type,
            'external_id' => $payload['external_id'] ?? null,
            'verification_status' => 'pending',
            'needs_review_fields' => $needsReviewFields ?: null,
            'ai_generated' => $payload['ai_generated'] ?? true,
            'confidence_score' => $payload['confidence_score'] ?? null,
            'is_active' => true,
        ]);

        $this->attachServiceImages($service, $payload['images'] ?? []);

        VerificationLog::record(
            record: $service,
            action: 'created',
            oldStatus: null,
            newStatus: 'pending',
            actorType: ($payload['ai_generated'] ?? true) ? 'ai' : 'system',
            actorName: $job->workflow_name,
            importJobId: $job->id,
        );

        $job->increment('total_created');

        return ['status' => 'created', 'id' => $service->id, 'reasons' => []];
    }

    /**
     * سجل موجود مسبقًا لنفس المصدر + المعرف الخارجي: لا يُنشأ سجل جديد أبدًا.
     * التحديث الفعلي للحقول لا يحدث إلا عبر Workflow تحقق صريح (badyah.reverify)،
     * وحتى وقتها لا يُغيَّر verification_status لسجل معتمد بالفعل تلقائيًا.
     */
    private function handleExistingSite(TouristSite $existing, array $payload, ImportJob $job, ?DataSource $dataSource): array
    {
        if (!str_contains($job->workflow_name, 'reverify')) {
            $job->increment('total_duplicates');
            return ['status' => 'duplicate', 'id' => $existing->id, 'reasons' => ['سجل مطابق موجود مسبقًا لنفس المصدر والمعرف الخارجي.']];
        }

        $factualFields = collect($payload)->only(['website_url', 'location', 'latitude', 'longitude'])->filter(fn ($v) => $v !== null)->toArray();
        $changed = array_diff_assoc(array_map('strval', $factualFields), array_map('strval', $existing->only(array_keys($factualFields))));

        if ($changed) {
            $oldStatus = $existing->verification_status;
            $existing->fill($factualFields);
            $existing->last_verified_at = now();
            $existing->save();

            VerificationLog::record(
                record: $existing,
                action: 'updated',
                oldStatus: $oldStatus,
                newStatus: $existing->verification_status,
                changedFields: array_keys($changed),
                actorType: 'system',
                actorName: $job->workflow_name,
                importJobId: $job->id,
            );

            $job->increment('total_updated');

            return ['status' => 'updated', 'id' => $existing->id, 'reasons' => array_keys($changed)];
        }

        $existing->update(['last_verified_at' => now()]);
        $job->increment('total_duplicates');

        return ['status' => 'duplicate', 'id' => $existing->id, 'reasons' => ['لا تغييرات جوهرية، تم فقط تحديث تاريخ آخر تحقق.']];
    }

    private function handleExistingService(TouristService $existing, array $payload, ImportJob $job, ?DataSource $dataSource): array
    {
        if (!str_contains($job->workflow_name, 'reverify')) {
            $job->increment('total_duplicates');
            return ['status' => 'duplicate', 'id' => $existing->id, 'reasons' => ['سجل مطابق موجود مسبقًا لنفس المصدر والمعرف الخارجي.']];
        }

        $factualFields = collect($payload)->only(['website_url', 'phone', 'whatsapp', 'latitude', 'longitude'])->filter(fn ($v) => $v !== null)->toArray();
        $changed = array_diff_assoc(array_map('strval', $factualFields), array_map('strval', $existing->only(array_keys($factualFields))));

        if ($changed) {
            $oldStatus = $existing->verification_status;
            $existing->fill($factualFields);
            $existing->last_verified_at = now();
            $existing->save();

            VerificationLog::record(
                record: $existing,
                action: 'updated',
                oldStatus: $oldStatus,
                newStatus: $existing->verification_status,
                changedFields: array_keys($changed),
                actorType: 'system',
                actorName: $job->workflow_name,
                importJobId: $job->id,
            );

            $job->increment('total_updated');

            return ['status' => 'updated', 'id' => $existing->id, 'reasons' => array_keys($changed)];
        }

        $existing->update(['last_verified_at' => now()]);
        $job->increment('total_duplicates');

        return ['status' => 'duplicate', 'id' => $existing->id, 'reasons' => ['لا تغييرات جوهرية، تم فقط تحديث تاريخ آخر تحقق.']];
    }

    /**
     * قاعدة صارمة: كل سجل لازم مصدر واضح — إما source_url أو source_name، وإلا يُرفض.
     */
    private function requireSource(array $payload): ?string
    {
        $hasSource = !empty($payload['source_url']) || !empty($payload['source_name']) || !empty($payload['data_source']);

        return $hasSource ? null : 'لا يمكن قبول السجل بدون مصدر واضح (source_url أو source_name أو data_source).';
    }

    private function resolveDataSource(?array $data): ?DataSource
    {
        if (!$data) {
            return null;
        }

        return DataSource::firstOrCreate(
            ['slug' => $data['slug']],
            [
                'name' => $data['name'],
                'type' => $data['type'] ?? 'other',
                'base_url' => $data['base_url'] ?? null,
                'trust_level' => $data['trust_level'] ?? 3,
                'is_active' => true,
            ]
        );
    }

    /**
     * تصنيف الموقع (مثال: قلعة، حصن، شاطئ...). قائمة مضبوطة مسبقًا، ليست
     * توليدًا حرًا — الذكاء الاصطناعي يختار من تصنيفات معروفة فقط.
     */
    private function resolveSiteCategory(?array $data): ?TouristSiteCategory
    {
        if (!$data) {
            return null;
        }

        return TouristSiteCategory::firstOrCreate(
            ['slug' => $data['slug']],
            [
                'name_ar' => $data['name_ar'],
                'name_en' => $data['name_en'],
            ]
        );
    }

    /**
     * يفحص الحقول غير المؤكدة تلقائيًا بدل تخمينها: إحداثيات خارج نطاق عمان
     * تقريبًا، أو ثقة منخفضة، تُعلَّم لمراجعة الأدمن بدل رفضها بالكامل.
     */
    private function detectNeedsReview(array $payload): array
    {
        $flags = [];

        if (empty($payload['wilayat_id'])) {
            $flags[] = 'wilayat_id';
        }

        if (empty($payload['latitude']) || empty($payload['longitude'])) {
            $flags[] = 'latitude';
            $flags[] = 'longitude';
        }

        if (!empty($payload['latitude']) && !empty($payload['longitude'])) {
            $lat = (float) $payload['latitude'];
            $lng = (float) $payload['longitude'];
            if ($lat < self::MIN_LAT || $lat > self::MAX_LAT || $lng < self::MIN_LNG || $lng > self::MAX_LNG) {
                $flags[] = 'latitude';
                $flags[] = 'longitude';
            }
        }

        if (isset($payload['confidence_score']) && $payload['confidence_score'] < 50) {
            $flags[] = 'overall_confidence_low';
        }

        if (!empty($payload['needs_review_fields']) && is_array($payload['needs_review_fields'])) {
            $flags = array_merge($flags, $payload['needs_review_fields']);
        }

        return array_values(array_unique($flags));
    }

    private function normalizeName(string $name): string
    {
        return trim(preg_replace('/\s+/u', ' ', mb_strtolower($name)));
    }

    private function attachImages(TouristSite $site, array $images): void
    {
        foreach ($images as $index => $image) {
            $site->images()->create([
                'image_url' => $image['url'],
                'alt_text_ar' => $image['alt_text_ar'] ?? null,
                'is_featured' => $index === 0,
            ]);
        }
    }

    private function attachServiceImages(TouristService $service, array $images): void
    {
        foreach ($images as $index => $image) {
            TouristServiceImage::create([
                'tourist_service_id' => $service->id,
                'image_url' => $image['url'],
                'alt_text_ar' => $image['alt_text_ar'] ?? null,
                'is_featured' => $index === 0,
            ]);
        }
    }
}
