<?php

namespace App\Http\Controllers\Api\Badyah;

use App\Http\Controllers\Controller;
use App\Models\ImportJob;
use App\Services\Badyah\IngestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Ingest API لمحرك البادية الذكي فقط.
 *
 * قاعدة صارمة وغير قابلة للتفاوض: هذا الكونترولر لا ينشر أي شيء مباشرة.
 * كل سجل يدخل من هنا verification_status = pending وينتظر اعتماد الأدمن
 * من لوحة المراجعة. لا يوجد أي مسار هنا يغيّر حالة سجل إلى approved.
 */
class IngestController extends Controller
{
    public function __construct(private readonly IngestService $ingestService)
    {
    }

    public function touristSites(Request $request)
    {
        return $this->handleBatch($request, 'tourist_site', fn (array $item, ImportJob $job) => $this->ingestService->ingestTouristSite($item, $job));
    }

    public function touristServices(Request $request)
    {
        return $this->handleBatch($request, 'tourist_service', fn (array $item, ImportJob $job) => $this->ingestService->ingestTouristService($item, $job));
    }

    private function handleBatch(Request $request, string $recordType, callable $handler)
    {
        $validator = Validator::make($request->all(), [
            'job' => ['required', 'array'],
            'job.job_uuid' => ['required', 'uuid'],
            'job.workflow_name' => ['required', 'string', 'max:255'],
            'job.governorate_id' => ['nullable', 'integer', 'exists:governorates,id'],
            'job.wilayat_id' => ['nullable', 'integer', 'exists:wilayats,id'],
            'items' => ['required', 'array', 'min:1', 'max:100'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات الطلب غير صالحة.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $jobData = $request->input('job');
        $items = $request->input('items');

        $job = ImportJob::firstOrNew(['job_uuid' => $jobData['job_uuid']]);
        $job->fill([
            'workflow_name' => $jobData['workflow_name'],
            'record_type' => $recordType,
            'governorate_id' => $jobData['governorate_id'] ?? $job->governorate_id,
            'wilayat_id' => $jobData['wilayat_id'] ?? $job->wilayat_id,
        ]);
        if (!$job->exists) {
            $job->status = 'queued';
        }
        $job->save();
        $job->markRunning();

        $results = [];
        foreach ($items as $item) {
            $job->increment('total_fetched');

            try {
                $result = $handler($item, $job);
            } catch (\Throwable $e) {
                $job->increment('total_failed');
                $result = ['status' => 'failed', 'id' => null, 'reasons' => [$e->getMessage()]];
            }

            if ($result['status'] === 'rejected') {
                $job->increment('total_rejected');
            }

            $results[] = array_merge(['item' => $item['external_id'] ?? $item['name_ar'] ?? null], $result);
        }

        $job->refresh();
        $job->markFinished();

        return response()->json([
            'success' => true,
            'job' => [
                'job_uuid' => $job->job_uuid,
                'status' => $job->status,
                'total_fetched' => $job->total_fetched,
                'total_created' => $job->total_created,
                'total_updated' => $job->total_updated,
                'total_duplicates' => $job->total_duplicates,
                'total_rejected' => $job->total_rejected,
                'total_failed' => $job->total_failed,
            ],
            'results' => $results,
        ]);
    }
}
