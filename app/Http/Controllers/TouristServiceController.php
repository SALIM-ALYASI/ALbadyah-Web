<?php

namespace App\Http\Controllers;

use App\Models\TouristService;
use App\Models\Governorate;
use App\Models\Wilayat;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class TouristServiceController extends Controller
{
    /**
     * عرض قائمة الخدمات السياحية
     */
    public function index(Request $request)
    {
        $query = TouristService::query()
            ->with(['serviceType','governorate','wilayat']);

        // البحث في الاسم
        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name_ar', 'like', "%$search%")
                   ->orWhere('name_en', 'like', "%$search%");
            });
        }

        // الفلترة
        foreach (['governorate_id','wilayat_id','service_type_id'] as $filter) {
            if ($val = $request->query($filter)) {
                $query->where($filter, $val);
            }
        }

        $touristServices = $query->latest()->get();
        
        // للحصول على قوائم الفلترة
        $governorates = Governorate::all();
        $wilayats = Wilayat::all();
        $serviceTypes = ServiceType::all();
        
        return view('tourist-services.index', compact('touristServices', 'governorates', 'wilayats', 'serviceTypes'));
    }

    /**
     * عرض نموذج إضافة خدمة سياحية جديدة
     */
    public function create()
    {
        $governorates = Governorate::all();
        $wilayats = Wilayat::all();
        $serviceTypes = ServiceType::all();
        return view('tourist-services.create', compact('governorates', 'wilayats', 'serviceTypes'));
    }

    /**
     * حفظ خدمة سياحية جديدة
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name_ar'         => 'required|string|max:255',
            'name_en'         => 'required|string|max:255',
            'website_url'     => 'nullable|url|max:255',
            'image_url'       => 'nullable|url|max:1024',
            'image'           => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'governorate_id'  => 'nullable|integer|exists:governorates,id',
            'wilayat_id'      => 'nullable|integer|exists:wilayats,id',
            'service_type_id' => 'nullable|integer|exists:service_types,id',
        ]);

        // رفع الصورة إذا تم اختيارها
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('images/tourist-services', $imageName, 'public');
            $data['image_path'] = $imagePath;
        }

        TouristService::create($data);

        return redirect()->route('tourist-services.index')
            ->with('success', 'تمت إضافة الخدمة السياحية بنجاح');
    }

    /**
     * عرض خدمة سياحية محددة
     */
    public function show($id)
    {
        $touristService = TouristService::with(['serviceType','governorate','wilayat'])->findOrFail($id);
        return view('tourist-services.show', compact('touristService'));
    }

    /**
     * عرض نموذج تعديل خدمة سياحية
     */
    public function edit($id)
    {
        $touristService = TouristService::findOrFail($id);
        $governorates = Governorate::all();
        $wilayats = Wilayat::all();
        $serviceTypes = ServiceType::all();
        return view('tourist-services.edit', compact('touristService', 'governorates', 'wilayats', 'serviceTypes'));
    }

    /**
     * تحديث خدمة سياحية
     */
    public function update(Request $request, $id)
    {
        $service = TouristService::findOrFail($id);

        $data = $request->validate([
            'name_ar'         => 'sometimes|string|max:255',
            'name_en'         => 'sometimes|string|max:255',
            'website_url'     => 'nullable|url|max:255',
            'image_url'       => 'nullable|url|max:1024',
            'image'           => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'governorate_id'  => 'nullable|integer|exists:governorates,id',
            'wilayat_id'      => 'nullable|integer|exists:wilayats,id',
            'service_type_id' => 'nullable|integer|exists:service_types,id',
        ]);

        // رفع الصورة الجديدة إذا تم اختيارها
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($service->image_path) {
                $oldImagePath = storage_path('app/public/' . $service->image_path);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('images/tourist-services', $imageName, 'public');
            $data['image_path'] = $imagePath;
        }

        $service->update($data);

        return redirect()->route('tourist-services.index')
            ->with('success', 'تم تحديث بيانات الخدمة السياحية بنجاح');
    }

    /**
     * حذف خدمة سياحية
     */
    public function destroy($id)
    {
        $service = TouristService::findOrFail($id);
        $service->delete();

        return redirect()->route('tourist-services.index')
            ->with('success', 'تم حذف الخدمة السياحية بنجاح');
    }

    /**
     * عرض جميع بيانات الجداول في صفحة واحدة
     */
    public function dataViewer()
    {
        // جلب جميع البيانات من الجداول
        $governorates = \App\Models\Governorate::withCount(['wilayats', 'touristSites', 'touristServices'])->get();
        $wilayats = \App\Models\Wilayat::with(['governorate'])->get();
        $touristSites = \App\Models\TouristSite::with(['governorate', 'wilayat', 'images'])->get();
        $touristServices = \App\Models\TouristService::with(['serviceType', 'governorate', 'wilayat'])->get();
        $serviceTypes = \App\Models\ServiceType::withCount('touristServices')->get();
        $touristImages = \App\Models\TouristImage::with('touristSite')->get();

        // إحصائيات عامة
        $stats = [
            'total_governorates' => $governorates->count(),
            'total_wilayats' => $wilayats->count(),
            'total_tourist_sites' => $touristSites->count(),
            'total_tourist_services' => $touristServices->count(),
            'total_service_types' => $serviceTypes->count(),
            'total_images' => $touristImages->count(),
        ];

        return view('data-viewer.index', compact(
            'governorates', 
            'wilayats', 
            'touristSites', 
            'touristServices', 
            'serviceTypes', 
            'touristImages', 
            'stats'
        ));
    }
}

