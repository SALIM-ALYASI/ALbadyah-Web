<?php

namespace App\Console\Commands;

use App\Models\Governorate;
use App\Models\ImportJob;
use App\Services\Badyah\AiEnrichmentService;
use App\Services\Badyah\Collectors\ExperienceOmanCollector;
use App\Services\Badyah\IngestService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * تجربة أولى محدودة لمحرك البادية: يجمع مواقع مسقط السياحية (وأي عناصر
 * تجارية تظهر بنفس القائمة، مُعلَّمة لا محذوفة) من Experience Oman فقط
 * (المصدر الأساسي MHT معطّل حاليًا)، يطابق الاسم العربي الرسمي إن وُجد،
 * يخصّب بالذكاء الاصطناعي (تصنيف + ترجمة احتياطية فقط)، ثم يرسل لـ
 * Ingest API الداخلي — كل سجل يبقى pending إلزاميًا.
 */
class BadyahMuscatPilot extends Command
{
    protected $signature = 'badyah:muscat-pilot {--limit=11} {--report= : مسار حفظ تقرير JSON}';

    protected $description = 'تجربة أولى: يجمع مواقع مسقط من Experience Oman، يطابق الاسم العربي الرسمي، يخصّب بـ AI، ويرسل لـ Ingest API كـ pending';

    public function handle(ExperienceOmanCollector $collector, AiEnrichmentService $ai, IngestService $ingestService): int
    {
        $limit = (int) $this->option('limit');
        $governorateSlug = 'muscat';

        $governorate = Governorate::where('name_en', 'Muscat')->orWhere('name_ar', 'مسقط')->first();
        if (!$governorate) {
            $this->error('محافظة مسقط غير موجودة في قاعدة البيانات. أنشئها أولًا.');
            return self::FAILURE;
        }

        $this->info("جمع حتى {$limit} عنصر من Experience Oman لمحافظة مسقط...");
        $rawItems = $collector->collect(['governorate_slug' => $governorateSlug, 'limit' => $limit]);
        $this->info('تم جمع '.count($rawItems).' عنصر خام.');

        $this->info('جمع مرشحي الاسم العربي الرسمي من نسخة الموقع العربية...');
        $arabicCandidates = $collector->collectArabicCandidates($governorateSlug);
        $this->info('تم جمع '.count($arabicCandidates).' مرشح عربي.');

        $sourceTrustLevel = (int) config('badyah.sources.experience_oman.trust_level');
        // "المصدر رسمي وموثوق" فقط — مو تقدير لصحة كل حقل بالسجل
        $sourceTrustScore = $sourceTrustLevel * 20; // 1-5 -> 20-100

        $job = ImportJob::create([
            'job_uuid' => (string) Str::uuid(),
            'workflow_name' => 'badyah.site_discovery.pilot',
            'record_type' => 'tourist_site',
            'governorate_id' => $governorate->id,
            'status' => 'queued',
        ]);
        $job->markRunning();

        $report = [];

        foreach ($rawItems as $rawItem) {
            $job->increment('total_fetched');
            $this->line("\n--- {$rawItem['name_en']} ---");

            try {
                if ($rawItem['is_tourism_candidate']) {
                    $officialArabic = $ai->matchOfficialArabicSource($rawItem, $arabicCandidates);
                    $enriched = $ai->enrichTouristSite($rawItem, $officialArabic);
                } else {
                    // عنصر تجاري: لا حاجة لترجمة/تصنيف AI، التصنيف معروف مسبقًا من الـ collector
                    $categoryConfig = config('badyah.site_categories.'.$rawItem['preset_category_slug']);
                    $officialArabic = $ai->matchOfficialArabicSource($rawItem, $arabicCandidates);
                    $enriched = [
                        'name_ar' => $officialArabic['name_ar'] ?? $rawItem['name_en'],
                        'description_ar' => $officialArabic['description_ar'] ?? null,
                        'description_en' => $rawItem['description_en'],
                        'name_ar_source' => $officialArabic ? 'official' : 'untranslated',
                        'category_slug' => $rawItem['preset_category_slug'],
                        'ai_confidence' => 100.0, // تصنيف تجاري واضح من الاسم، لا حاجة لثقة AI
                        'needs_review_fields' => $officialArabic ? [] : ['name_ar', 'description_ar'],
                    ];
                }
            } catch (\Throwable $e) {
                $this->error('فشل التخصيب بالذكاء الاصطناعي: '.$e->getMessage());
                $job->increment('total_failed');
                $report[] = ['before_ai' => $rawItem, 'after_ai' => null, 'ingest' => ['status' => 'failed', 'reasons' => [$e->getMessage()]]];
                continue;
            }

            $categoryConfig = config('badyah.site_categories.'.$enriched['category_slug']);

            $payload = [
                'name_ar' => $enriched['name_ar'],
                'name_en' => $rawItem['name_en'],
                'description_ar' => $enriched['description_ar'],
                'description_en' => $enriched['description_en'],
                'governorate_id' => $governorate->id,
                'wilayat_id' => null, // Experience Oman لا يوفر الولاية — يبقى needs_review
                'latitude' => $rawItem['latitude'],
                'longitude' => $rawItem['longitude'],
                'coordinates_source' => $rawItem['coordinates_source'],
                'source_url' => $rawItem['source_url'],
                'source_name' => $rawItem['source_name'],
                'source_type' => $rawItem['source_type'],
                'external_id' => Str::slug($rawItem['name_en']),
                'data_source' => [
                    'slug' => 'experience-oman',
                    'name' => 'Experience Oman',
                    'type' => 'official_tourism_authority',
                    'base_url' => 'https://experienceoman.om',
                    'trust_level' => $sourceTrustLevel,
                ],
                'category' => [
                    'slug' => $enriched['category_slug'],
                    'name_ar' => $categoryConfig['name_ar'],
                    'name_en' => $categoryConfig['name_en'],
                ],
                'ai_generated' => true,
                'source_trust_score' => $sourceTrustScore,
                'ai_confidence' => $enriched['ai_confidence'],
                'description_ar_generated' => $enriched['name_ar_source'] !== 'official' && $enriched['description_ar'] !== null,
                'name_ar_source' => $enriched['name_ar_source'],
                'is_tourism_candidate' => $rawItem['is_tourism_candidate'],
                'excluded_reason' => $rawItem['excluded_reason'],
                'collected_at' => $rawItem['collected_at'],
                'source_last_checked_at' => $rawItem['source_last_checked_at'],
                'collector_name' => $rawItem['collector_name'],
                'collector_version' => $rawItem['collector_version'],
                'needs_review_fields' => $enriched['needs_review_fields'],
            ];

            $result = $ingestService->ingestTouristSite($payload, $job);

            $this->line('  status: '.$result['status'].($result['id'] ? " (id={$result['id']})" : '').' | name_ar_source: '.$enriched['name_ar_source']);
            if ($result['reasons']) {
                $this->line('  reasons: '.implode(' | ', $result['reasons']));
            }

            $report[] = [
                'before_ai' => $rawItem,
                'after_ai' => $enriched,
                'ingest_payload' => $payload,
                'ingest_result' => $result,
            ];
        }

        $job->refresh();
        $job->markFinished();

        $this->newLine();
        $this->table(
            ['المقياس', 'العدد'],
            [
                ['total_fetched', $job->total_fetched],
                ['created', $job->total_created],
                ['updated', $job->total_updated],
                ['duplicates', $job->total_duplicates],
                ['rejected', $job->total_rejected],
                ['failed', $job->total_failed],
            ]
        );

        if ($reportPath = $this->option('report')) {
            file_put_contents($reportPath, json_encode([
                'job_uuid' => $job->job_uuid,
                'summary' => $job->only(['total_fetched', 'total_created', 'total_updated', 'total_duplicates', 'total_rejected', 'total_failed']),
                'items' => $report,
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            $this->info("تم حفظ التقرير: {$reportPath}");
        }

        return self::SUCCESS;
    }
}
