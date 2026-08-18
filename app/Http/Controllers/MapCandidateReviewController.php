<?php

namespace App\Http\Controllers;

use App\Models\MapCandidate;
use App\Models\ServiceType;
use App\Models\TouristSiteCategory;
use App\Services\MapCandidatePublisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MapCandidateReviewController extends Controller
{
    private const STATUSES = [
        'pending_review', 'needs_enrichment', 'approved_draft',
        'deferred', 'rejected', 'published',
    ];

    public function index(Request $request)
    {
        $query = MapCandidate::query()->with(['wilayat', 'governorate']);

        if (in_array($request->query('status'), self::STATUSES, true)) {
            $query->where('status', $request->query('status'));
        }
        if (in_array($request->query('category'), ['site', 'service'], true)) {
            $query->where('category', $request->query('category'));
        }
        if ($request->filled('q')) {
            $search = trim((string) $request->query('q'));
            $query->where(function ($builder) use ($search) {
                $builder->where('name_ar', 'like', "%{$search}%")
                    ->orWhere('name_en', 'like', "%{$search}%")
                    ->orWhere('osm_id', 'like', "%{$search}%");
            });
        }

        $candidates = $query->latest('id')->paginate(25)->withQueryString();
        $stats = [
            'waiting' => MapCandidate::whereIn('status', ['pending_review', 'needs_enrichment', 'approved_draft'])->count(),
            'needs_enrichment' => MapCandidate::where('status', 'needs_enrichment')->count(),
            'published' => MapCandidate::where('status', 'published')->count(),
            'rejected' => MapCandidate::where('status', 'rejected')->count(),
        ];

        return view('admin.map-candidates.index', compact('candidates', 'stats'));
    }

    public function edit(int $candidateId)
    {
        $candidate = MapCandidate::with(['wilayat', 'governorate'])->findOrFail($candidateId);

        return view('admin.map-candidates.edit', [
            'candidate' => $candidate,
            'governorates' => DB::table('governorates')->orderBy('name_ar')->get(),
            'wilayats' => DB::table('wilayats')->orderBy('name_ar')->get(),
            'siteCategories' => TouristSiteCategory::orderBy('name_ar')->get(),
            'serviceTypes' => ServiceType::orderBy('name_ar')->get(),
        ]);
    }

    public function process(Request $request, int $candidateId, MapCandidatePublisher $publisher)
    {
        $candidate = MapCandidate::findOrFail($candidateId);
        if ($candidate->status === 'published') {
            return back()->with('error', 'هذا المرشح نُقل إلى الموقع سابقًا ولا يمكن نشره مرة ثانية.');
        }

        $action = (string) $request->input('action', 'save');
        if (!in_array($action, ['save', 'save_review', 'publish'], true)) {
            abort(422, 'إجراء غير صالح.');
        }

        $descriptionRule = $action === 'publish'
            ? ['required', 'string', 'min:10', 'max:5000']
            : ['nullable', 'string', 'max:5000'];

        $data = $request->validate([
            'category' => ['required', Rule::in(['site', 'service'])],
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'description_ar' => $descriptionRule,
            'description_en' => $descriptionRule,
            'subtype' => ['nullable', 'string', 'max:100'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'phone' => ['nullable', 'string', 'max:60'],
            'website' => ['nullable', 'string', 'max:500'],
            'opening_hours' => ['nullable', 'string', 'max:255'],
            'address_ar' => ['nullable', 'string', 'max:500'],
            'wilayat_id' => ['required', 'integer', 'exists:wilayats,id'],
            'governorate_id' => ['nullable', 'integer', 'exists:governorates,id'],
            'tourist_site_category_id' => ['nullable', 'integer', 'exists:tourist_site_categories,id'],
            'service_type_id' => ['nullable', 'integer', 'exists:service_types,id'],
            'image_url' => ['nullable', 'string', 'max:500'],
        ]);

        if (empty($data['governorate_id'])) {
            $data['governorate_id'] = DB::table('wilayats')
                ->where('id', $data['wilayat_id'])
                ->value('governorate_id');
        }

        $data['missing_fields'] = collect([
            'description_ar', 'description_en', 'phone', 'website', 'image_url',
        ])->filter(fn ($field) => blank($data[$field] ?? null))->values()->all();

        $candidate->fill($data);
        if ($action === 'save' && in_array($candidate->status, ['rejected', 'deferred'], true)) {
            $candidate->status = 'approved_draft';
            $candidate->rejected_reason = null;
        }
        $candidate->save();

        if ($action === 'save') {
            return redirect()->route('map-candidates.edit', $candidate->id)
                ->with('success', 'تم حفظ تعديلات المسودة.');
        }

        $public = $action === 'publish';
        $finalRecord = $publisher->publish($candidate->id, $public);
        $message = $public
            ? 'تم حفظ السجل ونشره في الموقع بنجاح.'
            : 'تم حفظ السجل في بيانات الموقع بحالة مراجعة، ولن يظهر للعامة حتى اعتماده.';

        return redirect()->route('map-candidates.index')
            ->with('success', $message.' رقم السجل: '.$finalRecord->id);
    }

    public function reject(Request $request, int $candidateId)
    {
        $candidate = MapCandidate::findOrFail($candidateId);
        if ($candidate->status === 'published') {
            return back()->with('error', 'لا يمكن رفض مرشح نُشر بالفعل. عدّل السجل النهائي من لوحة الموقع.');
        }

        $data = $request->validate([
            'rejected_reason' => ['nullable', 'string', 'max:255'],
        ]);
        $candidate->update([
            'status' => 'rejected',
            'rejected_reason' => $data['rejected_reason'] ?? 'رفض من صفحة مراجعة البوت',
        ]);

        return redirect()->route('map-candidates.index')->with('success', 'تم رفض المرشح.');
    }

}
