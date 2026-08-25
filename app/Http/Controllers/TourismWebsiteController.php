<?php

namespace App\Http\Controllers;

use App\Models\Governorate;
use App\Models\Wilayat;
use App\Models\TouristSite;
use App\Models\TouristService;
use App\Models\ServiceType;
use App\Models\TouristSiteCategory;
use App\Support\ArabicText;
use Illuminate\Http\Request;

class TourismWebsiteController extends Controller
{
    /**
     * الصفحة الرئيسية للموقع السياحي
     */
    public function index()
    {
        try {
            // جلب البيانات المميزة للعرض في الصفحة الرئيسية (المعتمدة فقط)
            $featuredSites = TouristSite::with(['governorate', 'wilayat', 'images'])
                ->publiclyVisible()
                ->latest()
                ->take(6)
                ->get();

            $featuredServices = TouristService::with(['serviceType', 'governorate', 'wilayat'])
                ->publiclyVisible()
                ->latest()
                ->take(6)
                ->get();

            $governorates = Governorate::withCount([
                    'wilayats',
                    'touristSites as tourist_sites_count' => fn ($q) => $q->publiclyVisible(),
                    'touristServices as tourist_services_count' => fn ($q) => $q->publiclyVisible(),
                ])
                ->orderBy('name_ar', 'desc')
                ->get();

            $serviceTypes = ServiceType::withCount([
                    'touristServices as tourist_services_count' => fn ($q) => $q->publiclyVisible(),
                ])
                ->having('tourist_services_count', '>', 0)
                ->orderBy('name_ar')
                ->get();

            $stats = [
                'total_governorates' => Governorate::count(),
                'total_wilayats' => Wilayat::count(),
                'total_tourist_sites' => TouristSite::publiclyVisible()->count(),
                'total_tourist_services' => TouristService::publiclyVisible()->count(),
            ];

            return view('tourism.index', compact('featuredSites', 'featuredServices', 'governorates', 'serviceTypes', 'stats'));
        } catch (\Exception $e) {
            // في حالة وجود خطأ، إرجاع بيانات فارغة
            $featuredSites = collect();
            $featuredServices = collect();
            $governorates = collect();
            $serviceTypes = collect();
            $stats = [
                'total_governorates' => 0,
                'total_wilayats' => 0,
                'total_tourist_sites' => 0,
                'total_tourist_services' => 0,
            ];

            return view('tourism.index', compact('featuredSites', 'featuredServices', 'governorates', 'serviceTypes', 'stats'));
        }
    }

    /**
     * عرض جميع المحافظات
     */
    public function governorates()
    {
        $governorates = Governorate::with(['wilayats'])
            ->withCount([
                'wilayats',
                'touristSites as tourist_sites_count' => fn ($q) => $q->publiclyVisible(),
                'touristServices as tourist_services_count' => fn ($q) => $q->publiclyVisible(),
            ])
            ->orderBy('name_ar', 'desc')
            ->get();

        return view('tourism.governorates', compact('governorates'));
    }

    /**
     * عرض محافظة محددة
     */
    public function governorate($identifier)
    {
        $governorate = Governorate::withCount([
                'wilayats',
                'touristSites as tourist_sites_count' => fn ($q) => $q->publiclyVisible(),
                'touristServices as tourist_services_count' => fn ($q) => $q->publiclyVisible(),
            ])
            ->where(function($query) use ($identifier) {
                $query->where('id', $identifier)
                      ->orWhere('slug', $identifier);
            })
            ->firstOrFail();

        $governorate->setRelation('wilayats', $governorate->wilayats()
            ->withCount([
                'touristSites as tourist_sites_count' => fn ($q) => $q->publiclyVisible(),
                'touristServices as tourist_services_count' => fn ($q) => $q->publiclyVisible(),
            ])
            ->orderBy('name_ar')
            ->get());

        $featuredSites = $governorate->visibleTouristSites()
            ->with(['wilayat', 'images'])
            ->take(4)
            ->get();

        $featuredServices = $governorate->visibleTouristServices()
            ->with(['serviceType', 'wilayat'])
            ->take(4)
            ->get();

        return view('tourism.governorate', compact('governorate', 'featuredSites', 'featuredServices'));
    }

    /**
     * عرض جميع الولايات
     */
    public function wilayats(Request $request)
    {
        $query = Wilayat::with('governorate')
            ->withCount([
                'touristSites as tourist_sites_count' => fn ($q) => $q->publiclyVisible(),
                'touristServices as tourist_services_count' => fn ($q) => $q->publiclyVisible(),
            ]);

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name_ar', 'like', "%{$search}%")
                  ->orWhere('name_en', 'like', "%{$search}%");
            });
        }

        // فلترة حسب المحافظة
        if ($request->filled('governorate_id')) {
            $query->where('governorate_id', $request->governorate_id);
        }

        $wilayats = $query->orderBy('name_ar')->paginate(12)->withQueryString();
        $governorates = Governorate::orderBy('name_ar')->get();

        return view('tourism.wilayats', compact('wilayats', 'governorates'));
    }

    /**
     * عرض ولاية محددة
     */
    public function wilayat($identifier)
    {
        $wilayat = Wilayat::with(['governorate'])
            ->withCount([
                'touristSites as tourist_sites_count' => fn ($q) => $q->publiclyVisible(),
                'touristServices as tourist_services_count' => fn ($q) => $q->publiclyVisible(),
            ])
            ->where(function($query) use ($identifier) {
                $query->where('id', $identifier)
                      ->orWhere('slug', $identifier);
            })
            ->firstOrFail();

        $wilayat->setRelation('touristSites', $wilayat->visibleTouristSites()->with(['images', 'category'])->get());
        $wilayat->setRelation('touristServices', $wilayat->visibleTouristServices()->with('serviceType')->get());

        return view('tourism.wilayat', compact('wilayat'));
    }

    /**
     * عرض جميع المواقع السياحية
     */
    public function touristSites(Request $request)
    {
        $query = TouristSite::with(['governorate', 'wilayat', 'images'])->publiclyVisible();

        // فلترة حسب المحافظة
        if ($request->has('governorate_id') && $request->governorate_id) {
            $query->where('governorate_id', $request->governorate_id);
        }

        // فلترة حسب الولاية
        if ($request->has('wilayat_id') && $request->wilayat_id) {
            $query->where('wilayat_id', $request->wilayat_id);
        }

        // فلترة حسب التصنيف
        if ($request->has('category_id') && $request->category_id) {
            $query->where('tourist_site_category_id', $request->category_id);
        }

        // البحث في الاسم
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_ar', 'like', "%$search%")
                   ->orWhere('name_en', 'like', "%$search%")
                   ->orWhere('description_ar', 'like', "%$search%");
            });
        }

        $touristSites = $query->latest()->paginate(12)->withQueryString();
        $totalSitesCount = TouristSite::publiclyVisible()->count();

        // للحصول على قوائم الفلترة
        $governorates = Governorate::orderBy('name_ar')->get();
        $wilayats = Wilayat::orderBy('name_ar')->get();
        $categories = TouristSiteCategory::whereHas('touristSites', fn ($q) => $q->publiclyVisible())->orderBy('name_ar')->get();

        return view('tourism.tourist-sites', compact('touristSites', 'governorates', 'wilayats', 'categories', 'totalSitesCount'));
    }

    /**
     * عرض موقع سياحي محدد
     */
    public function touristSite($identifier)
    {
        $touristSite = TouristSite::with(['governorate', 'wilayat', 'images', 'category'])
            ->publiclyVisible()
            ->where(function($query) use ($identifier) {
                $query->where('id', $identifier)
                      ->orWhere('slug', $identifier);
            })
            ->firstOrFail();

        // مواقع مماثلة في نفس المحافظة
        $relatedSites = TouristSite::with(['images'])
            ->publiclyVisible()
            ->where('governorate_id', $touristSite->governorate_id)
            ->where('id', '!=', $touristSite->id)
            ->take(4)
            ->get();

        // خدمات قريبة في نفس المحافظة
        $nearbyServices = TouristService::with(['serviceType', 'wilayat'])
            ->publiclyVisible()
            ->where('governorate_id', $touristSite->governorate_id)
            ->take(4)
            ->get();

        return view('tourism.tourist-site', compact('touristSite', 'relatedSites', 'nearbyServices'));
    }

    /**
     * عرض جميع الخدمات السياحية
     */
    public function touristServices(Request $request)
    {
        $query = TouristService::with(['serviceType', 'governorate', 'wilayat'])->publiclyVisible();

        // فلترة حسب نوع الخدمة
        if ($request->has('service_type_id') && $request->service_type_id) {
            $query->where('service_type_id', $request->service_type_id);
        }

        // فلترة حسب المحافظة
        if ($request->has('governorate_id') && $request->governorate_id) {
            $query->where('governorate_id', $request->governorate_id);
        }

        // فلترة حسب الولاية
        if ($request->has('wilayat_id') && $request->wilayat_id) {
            $query->where('wilayat_id', $request->wilayat_id);
        }

        // البحث في الاسم
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_ar', 'like', "%$search%")
                   ->orWhere('name_en', 'like', "%$search%");
            });
        }

        $touristServices = $query->latest()->paginate(12)->withQueryString();
        $totalServicesCount = TouristService::publiclyVisible()->count();

        // للحصول على قوائم الفلترة - فقط الأنواع التي تحتوي على خدمات
        $serviceTypes = ServiceType::withCount(['touristServices as tourist_services_count' => fn ($q) => $q->publiclyVisible()])
            ->having('tourist_services_count', '>', 0)
            ->orderBy('name_ar')
            ->get();
        $governorates = Governorate::whereHas('touristServices', fn ($q) => $q->publiclyVisible())->orderBy('name_ar')->get();
        $wilayats = Wilayat::whereHas('touristServices', fn ($q) => $q->publiclyVisible())->orderBy('name_ar')->get();

        return view('tourism.tourist-services', compact('touristServices', 'serviceTypes', 'governorates', 'wilayats', 'totalServicesCount'));
    }

    /**
     * عرض خدمة سياحية محددة
     */
    public function touristService($identifier)
    {
        $touristService = TouristService::with(['serviceType', 'governorate', 'wilayat'])
            ->publiclyVisible()
            ->where(function($query) use ($identifier) {
                $query->where('id', $identifier)
                      ->orWhere('slug', $identifier);
            })
            ->firstOrFail();

        // مواقع سياحية قريبة في نفس المحافظة
        $nearbySites = TouristSite::with(['images', 'wilayat'])
            ->publiclyVisible()
            ->where('governorate_id', $touristService->governorate_id)
            ->take(4)
            ->get();

        return view('tourism.tourist-service', compact('touristService', 'nearbySites'));
    }


    /**
     * صفحة البحث (تُعرض أيضاً عند وجود query عبر رابط النتائج القديم).
     */
    public function search(Request $request)
    {
        $query = trim((string) $request->get('query', ''));
        $tab = $request->get('tab', 'all');

        [$touristSites, $touristServices, $governoratesResults, $wilayatsResults] = $this->performSearch($query, $tab);

        return view('tourism.search', compact('query', 'tab', 'touristSites', 'touristServices', 'governoratesResults', 'wilayatsResults'));
    }

    /**
     * رابط قديم لنتائج البحث — يُبقي نفس السلوك عبر نفس الصفحة.
     */
    public function searchResults(Request $request)
    {
        return $this->search($request);
    }

    /**
     * اقتراحات بحث سريعة (JSON) لشريط البحث في الصفحة الرئيسية.
     */
    public function searchSuggest(Request $request)
    {
        $query = trim((string) $request->get('query', ''));

        if ($query === '') {
            return response()->json(['groups' => []]);
        }

        [$touristSites, $touristServices, $governoratesResults, $wilayatsResults] = $this->performSearch($query, 'all', 5);

        $groups = [];

        if ($touristSites->isNotEmpty()) {
            $groups[] = [
                'title' => 'المواقع السياحية',
                'items' => $touristSites->map(fn ($s) => [
                    'name' => $s->name_ar,
                    'meta' => $s->wilayat?->name_ar,
                    'url' => route('tourism.tourist-site', $s->slug ?: $s->id),
                    'image' => $s->images->isNotEmpty() ? $s->featured_image : null,
                ]),
            ];
        }

        if ($wilayatsResults->isNotEmpty()) {
            $groups[] = [
                'title' => 'الولايات',
                'items' => $wilayatsResults->map(fn ($w) => [
                    'name' => $w->name_ar,
                    'meta' => $w->governorate?->name_ar,
                    'url' => route('tourism.wilayat', $w->slug ?: $w->id),
                    'image' => null,
                ]),
            ];
        }

        if ($touristServices->isNotEmpty()) {
            $groups[] = [
                'title' => 'الخدمات',
                'items' => $touristServices->map(fn ($s) => [
                    'name' => $s->name_ar,
                    'meta' => $s->serviceType?->name_ar,
                    'url' => route('tourism.tourist-service', $s->slug ?: $s->id),
                    'image' => $s->has_image ? $s->image_url : null,
                ]),
            ];
        }

        if ($governoratesResults->isNotEmpty()) {
            $groups[] = [
                'title' => 'المحافظات',
                'items' => $governoratesResults->map(fn ($g) => [
                    'name' => $g->name_ar,
                    'meta' => null,
                    'url' => route('tourism.governorate', $g->slug ?: $g->id),
                    'image' => $g->has_image ? $g->image_url : null,
                ]),
            ];
        }

        return response()->json(['groups' => $groups]);
    }

    /**
     * منطق البحث الموحّد عبر أربعة أنواع من السجلات، مع توحيد صيغ الألف/الياء العربية
     * حتى يتطابق البحث بغض النظر عن اختلاف كتابة المستخدم.
     */
    private function performSearch(string $query, string $tab = 'all', int $limit = 6): array
    {
        $touristSites = collect();
        $touristServices = collect();
        $governoratesResults = collect();
        $wilayatsResults = collect();

        if ($query === '') {
            return [$touristSites, $touristServices, $governoratesResults, $wilayatsResults];
        }

        if (in_array($tab, ['all', 'sites'], true)) {
            $touristSites = TouristSite::with(['governorate', 'wilayat', 'images'])
                ->publiclyVisible()
                ->get()
                ->filter(fn ($s) => ArabicText::contains($s->name_ar, $query) || ArabicText::contains($s->name_en, $query))
                ->take($limit)
                ->values();
        }

        if (in_array($tab, ['all', 'services'], true)) {
            $touristServices = TouristService::with(['serviceType', 'governorate', 'wilayat'])
                ->publiclyVisible()
                ->get()
                ->filter(fn ($s) => ArabicText::contains($s->name_ar, $query) || ArabicText::contains($s->name_en, $query))
                ->take($limit)
                ->values();
        }

        if (in_array($tab, ['all', 'wilayats'], true)) {
            $wilayatsResults = Wilayat::with('governorate')
                ->get()
                ->filter(fn ($w) => ArabicText::contains($w->name_ar, $query) || ArabicText::contains($w->name_en, $query))
                ->take($limit)
                ->values();
        }

        if (in_array($tab, ['all', 'governorates'], true)) {
            $governoratesResults = Governorate::all()
                ->filter(fn ($g) => ArabicText::contains($g->name_ar, $query) || ArabicText::contains($g->name_en, $query))
                ->take($limit)
                ->values();
        }

        return [$touristSites, $touristServices, $governoratesResults, $wilayatsResults];
    }

    /**
     * صفحة من نحن
     */
    public function about()
    {
        $stats = [
            'total_governorates' => Governorate::count(),
            'total_wilayats' => Wilayat::count(),
            'total_tourist_sites' => TouristSite::publiclyVisible()->count(),
            'total_tourist_services' => TouristService::publiclyVisible()->count(),
        ];

        return view('tourism.about', compact('stats'));
    }
}
