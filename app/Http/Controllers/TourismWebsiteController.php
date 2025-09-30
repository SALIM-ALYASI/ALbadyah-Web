<?php

namespace App\Http\Controllers;

use App\Models\Governorate;
use App\Models\Wilayat;
use App\Models\TouristSite;
use App\Models\TouristService;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class TourismWebsiteController extends Controller
{
    /**
     * الصفحة الرئيسية للموقع السياحي
     */
    public function index()
    {
        try {
            // جلب البيانات المميزة للعرض في الصفحة الرئيسية
            $featuredSites = TouristSite::with(['governorate', 'wilayat', 'images'])
                ->latest()
                ->take(6)
                ->get();

            $governorates = Governorate::withCount(['touristSites', 'touristServices'])
                ->orderBy('name_ar', 'desc')
                ->get();

            $stats = [
                'total_governorates' => Governorate::count(),
                'total_wilayats' => Wilayat::count(),
                'total_tourist_sites' => TouristSite::count(),
                'total_tourist_services' => TouristService::count(),
            ];

            return view('tourism.index', compact('featuredSites', 'governorates', 'stats'));
        } catch (\Exception $e) {
            // في حالة وجود خطأ، إرجاع بيانات فارغة
            $featuredSites = collect();
            $governorates = collect();
            $stats = [
                'total_governorates' => 0,
                'total_wilayats' => 0,
                'total_tourist_sites' => 0,
                'total_tourist_services' => 0,
            ];

            return view('tourism.index', compact('featuredSites', 'governorates', 'stats'));
        }
    }

    /**
     * عرض جميع المحافظات
     */
    public function governorates()
    {
        $governorates = Governorate::with(['wilayats', 'touristSites', 'touristServices'])
            ->withCount(['wilayats', 'touristSites', 'touristServices'])
            ->orderBy('name_ar', 'desc')
            ->get();

        return view('tourism.governorates', compact('governorates'));
    }

    /**
     * عرض محافظة محددة
     */
    public function governorate($identifier)
    {
        $governorate = Governorate::with(['wilayats', 'touristSites', 'touristServices'])
            ->withCount(['wilayats', 'touristSites', 'touristServices'])
            ->where(function($query) use ($identifier) {
                $query->where('id', $identifier)
                      ->orWhere('slug', $identifier);
            })
            ->firstOrFail();

        $featuredSites = $governorate->touristSites()
            ->with(['images'])
            ->whereHas('images')
            ->take(4)
            ->get();

        return view('tourism.governorate', compact('governorate', 'featuredSites'));
    }

    /**
     * عرض جميع الولايات
     */
    public function wilayats(Request $request)
    {
        $query = Wilayat::with(['governorate', 'touristSites', 'touristServices']);

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name_ar', 'like', "%{$search}%")
                  ->orWhere('name_en', 'like', "%{$search}%")
                  ->orWhere('description_ar', 'like', "%{$search}%");
            });
        }

        // فلترة حسب المحافظة
        if ($request->filled('governorate')) {
            $query->where('governorate_id', $request->governorate);
        }

        // الترتيب
        $sort = $request->get('sort', 'name');
        switch ($sort) {
            case 'sites':
                $query->withCount('touristSites')->orderByDesc('tourist_sites_count');
                break;
            case 'services':
                $query->withCount('touristServices')->orderByDesc('tourist_services_count');
                break;
            default:
                $query->orderBy('name_ar');
                break;
        }

        $wilayats = $query->paginate(12);
        $governorates = \App\Models\Governorate::orderBy('name_ar')->get();

        return view('tourism.wilayats', compact('wilayats', 'governorates'));
    }

    /**
     * عرض ولاية محددة
     */
    public function wilayat($identifier)
    {
        $wilayat = Wilayat::with(['governorate', 'touristSites', 'touristServices'])
            ->where(function($query) use ($identifier) {
                $query->where('id', $identifier)
                      ->orWhere('slug', $identifier);
            })
            ->firstOrFail();

        return view('tourism.wilayat', compact('wilayat'));
    }

    /**
     * عرض جميع المواقع السياحية
     */
    public function touristSites(Request $request)
    {
        $query = TouristSite::with(['governorate', 'wilayat', 'images']);

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
                   ->orWhere('name_en', 'like', "%$search%")
                   ->orWhere('description_ar', 'like', "%$search%");
            });
        }

        $touristSites = $query->latest()->paginate(12);

        // للحصول على قوائم الفلترة
        $governorates = Governorate::orderBy('name_ar')->get();
        $wilayats = Wilayat::orderBy('name_ar')->get();

        return view('tourism.tourist-sites', compact('touristSites', 'governorates', 'wilayats'));
    }

    /**
     * عرض موقع سياحي محدد
     */
    public function touristSite($identifier)
    {
        $touristSite = TouristSite::with(['governorate', 'wilayat', 'images'])
            ->where(function($query) use ($identifier) {
                $query->where('id', $identifier)
                      ->orWhere('slug', $identifier);
            })
            ->firstOrFail();

        // مواقع مماثلة في نفس المحافظة
        $relatedSites = TouristSite::with(['images'])
            ->where('governorate_id', $touristSite->governorate_id)
            ->where('id', '!=', $touristSite->id)
            ->take(4)
            ->get();

        return view('tourism.tourist-site', compact('touristSite', 'relatedSites'));
    }

    /**
     * عرض جميع الخدمات السياحية
     */
    public function touristServices(Request $request)
    {
        $query = TouristService::with(['serviceType', 'governorate', 'wilayat']);

        // فلترة حسب نوع الخدمة
        if ($request->has('service_type_id') && $request->service_type_id) {
            $query->where('service_type_id', $request->service_type_id);
        }

        // فلترة حسب المحافظة
        if ($request->has('governorate_id') && $request->governorate_id) {
            $query->where('governorate_id', $request->governorate_id);
        }

        // البحث في الاسم
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_ar', 'like', "%$search%")
                   ->orWhere('name_en', 'like', "%$search%");
            });
        }

        $touristServices = $query->latest()->paginate(12);

        // للحصول على قوائم الفلترة - فقط الأنواع التي تحتوي على خدمات
        $serviceTypes = ServiceType::whereHas('touristServices')->orderBy('name_ar')->get();
        $governorates = Governorate::whereHas('touristServices')->orderBy('name_ar')->get();

        return view('tourism.tourist-services', compact('touristServices', 'serviceTypes', 'governorates'));
    }

    /**
     * عرض خدمة سياحية محددة
     */
    public function touristService($identifier)
    {
        $touristService = TouristService::with(['serviceType', 'governorate', 'wilayat'])
            ->where(function($query) use ($identifier) {
                $query->where('id', $identifier)
                      ->orWhere('slug', $identifier);
            })
            ->firstOrFail();

        // خدمات مماثلة من نفس النوع
        $relatedServices = TouristService::with(['serviceType'])
            ->where('service_type_id', $touristService->service_type_id)
            ->where('id', '!=', $touristService->id)
            ->take(4)
            ->get();

        return view('tourism.tourist-service', compact('touristService', 'relatedServices'));
    }


    /**
     * صفحة تفاصيل الولاية مع المواقع والخدمات
     */
    public function wilayatDetails($governorate_id)
    {
        $governorate = \App\Models\Governorate::with(['wilayats.touristSites', 'wilayats.touristServices'])
            ->findOrFail($governorate_id);
            
        return view('tourism.wilayat-details', compact('governorate'));
    }

    /**
     * صفحة البحث
     */
    public function search()
    {
        return view('tourism.search');
    }

    /**
     * نتائج البحث
     */
    public function searchResults(Request $request)
    {
        $query = $request->get('query', '');
        
        $touristSites = collect();
        $touristServices = collect();

        if ($query) {
            // البحث في المواقع السياحية
            $touristSites = TouristSite::with(['governorate', 'wilayat', 'images'])
                ->where(function ($q) use ($query) {
                    $q->where('name_ar', 'like', "%$query%")
                       ->orWhere('name_en', 'like', "%$query%")
                       ->orWhere('description_ar', 'like', "%$query%")
                       ->orWhere('description_en', 'like', "%$query%");
                })
                ->latest()
                ->take(6)
                ->get();

            // البحث في الخدمات السياحية
            $touristServices = TouristService::with(['serviceType', 'governorate', 'wilayat'])
                ->where(function ($q) use ($query) {
                    $q->where('name_ar', 'like', "%$query%")
                       ->orWhere('name_en', 'like', "%$query%")
                       ->orWhere('description_ar', 'like', "%$query%")
                       ->orWhere('description_en', 'like', "%$query%");
                })
                ->latest()
                ->take(6)
                ->get();
        }

        return view('tourism.search', compact('touristSites', 'touristServices'));
    }

    /**
     * صفحة من نحن
     */
    public function about()
    {
        $stats = [
            'total_governorates' => Governorate::count(),
            'total_wilayats' => Wilayat::count(),
            'total_tourist_sites' => TouristSite::count(),
            'total_tourist_services' => TouristService::count(),
        ];

        return view('tourism.about', compact('stats'));
    }
}
