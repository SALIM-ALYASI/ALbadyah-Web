<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MapCandidate;
use App\Services\MapCandidatePublisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * طابور مسودات بوت البادية (map_candidates) - منفصل تمامًا عن
 * BadyahBotItemController/tourist_sites/tourist_services. لا يوجد هنا أي
 * التخزين الأولي لا ينشر شيئًا. النقل للجداول النهائية يتم فقط عبر publish()
 * بعد ضغط المشرف زر الحفظ أو النشر في تلجرام.
 */
class MapCandidateController extends Controller
{
    /**
     * إنشاء/تحديث مسودة (upsert بـ osm_type+osm_id) - يُنادى عند أول عرض
     * للمرشّح على الأدمن، قبل أي قرار منه. idempotent: نفس العنصر بنفس
     * الجلسة أو جلسة لاحقة يرجّع نفس السجل بدل تكراره.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => ['required', 'in:site,service'],
            'osm_type' => ['nullable', 'string', 'max:50'],
            'osm_id' => ['nullable', 'string', 'max:100'],
            'wikidata_id' => ['nullable', 'string', 'max:50'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'subtype' => ['nullable', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'phone' => ['nullable', 'string', 'max:60'],
            'website' => ['nullable', 'string', 'max:255'],
            'opening_hours' => ['nullable', 'string', 'max:255'],
            'address_ar' => ['nullable', 'string', 'max:500'],
            'wilayat_id' => ['nullable', 'integer', 'exists:wilayats,id'],
            'governorate_id' => ['nullable', 'integer', 'exists:governorates,id'],
            'description_ar' => ['nullable', 'string', 'max:2000'],
            'description_en' => ['nullable', 'string', 'max:2000'],
            'image_url' => ['nullable', 'string', 'max:1000'],
            'image_urls' => ['nullable', 'array', 'max:10'],
            'image_urls.*' => ['string', 'url', 'max:2000'],
            'image_source' => ['nullable', 'string', 'max:100'],
            'image_license' => ['nullable', 'string', 'max:100'],
            'image_is_placeholder' => ['nullable', 'boolean'],
            'tourist_site_category_id' => ['nullable', 'integer', 'exists:tourist_site_categories,id'],
            'service_type_id' => ['nullable', 'integer', 'exists:service_types,id'],
            'sources' => ['nullable', 'array'],
            'field_confidence' => ['nullable', 'array'],
            'overall_confidence' => ['nullable', 'numeric', 'between:0,1'],
            'missing_fields' => ['nullable', 'array'],
            'telegram_admin_id' => ['nullable', 'string', 'max:60'],
            // status اختياري بالإنشاء: needs_enrichment لما Groq يفشل (البيانات
            // الخام تُحفظ فورًا بدل ما تُفقد)، وإلا الافتراضي pending_review.
            'status' => ['sometimes', 'in:pending_review,needs_enrichment'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات الطلب غير صالحة.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $status = $data['status'] ?? 'pending_review';

        $candidate = null;
        if (!empty($data['osm_type']) && !empty($data['osm_id'])) {
            $candidate = MapCandidate::where('osm_type', $data['osm_type'])
                ->where('osm_id', $data['osm_id'])->first();
        }

        if ($candidate) {
            // موجود مسبقًا (جلسة سابقة أو إعادة محاولة إثراء) - نحدّث بياناته
            // بس ما نلمس قرار الأدمن السابق لو كان اتخذ قرار فعلي (approved_
            // draft/rejected/deferred)، فقط لو لسه بانتظار المراجعة أو
            // بانتظار إثراء (retry).
            if (in_array($candidate->status, ['pending_review', 'needs_enrichment'], true)) {
                $candidate->fill($data);
                $candidate->status = $status;
                $candidate->save();
            }

            return response()->json([
                'success' => true,
                'duplicate' => true,
                'data' => $candidate,
            ]);
        }

        $data['status'] = $status;
        $candidate = MapCandidate::create($data);

        return response()->json([
            'success' => true,
            'duplicate' => false,
            'data' => $candidate,
        ], 201);
    }

    /**
     * تحديث حالة/حقل مسودة موجودة: قرار الأدمن (approved_draft/rejected/
     * deferred) أو تعديل حقل واحد بعد ✏️ تعديل بتلجرام.
     *
     * ملاحظة: بحث يدوي بدل implicit route-model-binding لنفس سبب
     * BadyahBotItemController - middleware group 'api' بهذا المشروع
     * (bootstrap/app.php) بدون SubstituteBindings::class.
     */
    public function update(Request $request, int $candidateId)
    {
        $candidate = MapCandidate::find($candidateId);
        if (!$candidate) {
            return response()->json(['success' => false, 'message' => 'مرشّح غير موجود.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => ['sometimes', 'in:pending_review,needs_enrichment,approved_draft,rejected,deferred'],
            'rejected_reason' => ['nullable', 'string', 'max:255'],
            'name_ar' => ['sometimes', 'string', 'max:255'],
            'name_en' => ['sometimes', 'string', 'max:255'],
            'description_ar' => ['sometimes', 'string', 'max:2000'],
            'description_en' => ['sometimes', 'string', 'max:2000'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:60'],
            'website' => ['sometimes', 'nullable', 'string', 'max:255'],
            'opening_hours' => ['sometimes', 'nullable', 'string', 'max:255'],
            'address_ar' => ['sometimes', 'nullable', 'string', 'max:500'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات الطلب غير صالحة.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $candidate->fill($validator->validated());
        $candidate->save();

        return response()->json(['success' => true, 'data' => $candidate]);
    }

    /**
     * نقل مباشر من تلجرام إلى جدول الموقع النهائي.
     * mode=review يحفظه غير ظاهر للعامة، وmode=public ينشر المكتمل فقط.
     */
    public function publish(Request $request, int $candidateId, MapCandidatePublisher $publisher)
    {
        $validator = Validator::make($request->all(), [
            'mode' => ['required', 'in:review,public'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'وضع الحفظ غير صالح.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $candidate = MapCandidate::find($candidateId);
        if (!$candidate) {
            return response()->json(['success' => false, 'message' => 'مرشّح غير موجود.'], 404);
        }

        $record = $publisher->publish($candidate->id, $request->input('mode') === 'public');

        return response()->json([
            'success' => true,
            'message' => $request->input('mode') === 'public'
                ? 'تم الحفظ والنشر في الموقع.'
                : 'تم الحفظ في بيانات الموقع للمراجعة.',
            'data' => [
                'candidate_id' => $candidate->id,
                'table' => $candidate->category === 'service' ? 'tourist_services' : 'tourist_sites',
                'id' => $record->id,
                'public' => $request->input('mode') === 'public',
            ],
        ], 201);
    }

    /**
     * قراءة فقط: مسودات ولاية محددة (كل الحالات) - يخدم فحص التكرار
     * (ضد كل شي سبق ترشيحه/رفضه) وأي عرض لاحق لقائمة المسودات.
     */
    public function index(Request $request)
    {
        $query = MapCandidate::query();

        if ($request->filled('wilayat_id')) {
            $query->where('wilayat_id', (int) $request->query('wilayat_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }
        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        $candidates = $query->orderByDesc('id')->limit(500)->get();

        return response()->json(['success' => true, 'candidates' => $candidates]);
    }
}
