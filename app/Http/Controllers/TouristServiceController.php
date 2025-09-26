<?php

namespace App\Http\Controllers;

use App\Models\TouristService;
use App\Models\Governorate;
use App\Models\Wilayat;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * عرض نموذج إضافة خدمة سياحية جديدة (الطريقة القديمة)
     */
    public function create()
    {
        $governorates = Governorate::all();
        $wilayats = Wilayat::all();
        $serviceTypes = ServiceType::all();
        return view('tourist-services.create', compact('governorates', 'wilayats', 'serviceTypes'));
    }

    /**
     * عرض نموذج إنشاء موقع خدمة سياحية جديد
     */
    public function createLocation()
    {
        $governorates = Governorate::all();
        $wilayats = Wilayat::all();
        $serviceTypes = ServiceType::all();
        return view('tourist-services.create-location', compact('governorates', 'wilayats', 'serviceTypes'));
    }

    /**
     * عرض نموذج إضافة خدمات لموقع محدد
     */
    public function addServices($id)
    {
        $location = TouristService::findOrFail($id);
        $serviceTypes = ServiceType::all();
        return view('tourist-services.add-services', compact('location', 'serviceTypes'));
    }

    /**
     * حفظ خدمة سياحية سريعة
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name_ar'         => 'required|string|max:255',
            'name_en'         => 'required|string|max:255',
            'website_url'     => 'nullable|url|max:255',
            'governorate_id'  => 'nullable|integer|exists:governorates,id',
            'wilayat_id'      => 'nullable|integer|exists:wilayats,id',
            'service_type_id' => 'nullable|integer|exists:service_types,id',
            'image_file'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_url'       => 'nullable|url|max:1024',
        ]);

        // معالجة صورة الخدمة
        if ($request->hasFile('image_file')) {
            $image = $request->file('image_file');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // إنشاء المجلد إذا لم يكن موجوداً
            $uploadPath = public_path('images/tourist-services');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // حفظ الصورة في مجلد public
            $image->move($uploadPath, $imageName);
            $data['image_path'] = 'images/tourist-services/' . $imageName;
        }

        TouristService::create($data);

        return redirect()->route('tourist-services.index')
            ->with('success', 'تمت إضافة الخدمة السياحية بنجاح');
    }

    /**
     * حفظ موقع خدمة سياحية جديد
     */
    public function storeLocation(Request $request)
    {
        $data = $request->validate([
            'name_ar'         => 'required|string|max:255',
            'name_en'         => 'required|string|max:255',
            'website_url'     => 'nullable|url|max:255',
            'governorate_id'  => 'nullable|integer|exists:governorates,id',
            'wilayat_id'      => 'nullable|integer|exists:wilayats,id',
            'service_type_id' => 'nullable|integer|exists:service_types,id',
            'location_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'location_image_url' => 'nullable|url|max:1024',
        ]);

        // معالجة صورة الموقع
        if ($request->hasFile('location_image')) {
            $image = $request->file('location_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // إنشاء المجلد إذا لم يكن موجوداً
            $uploadPath = public_path('images/tourist-services/locations');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // حفظ الصورة في مجلد public
            $image->move($uploadPath, $imageName);
            $data['location_image_path'] = 'images/tourist-services/locations/' . $imageName;
        }

        $location = TouristService::create($data);

        return redirect()->route('tourist-services.add-services', $location->id)
            ->with('success', 'تم إنشاء الموقع بنجاح. يمكنك الآن إضافة الخدمات.');
    }

    /**
     * حفظ خدمات متعددة لموقع محدد
     */
    public function storeServices(Request $request, $id)
    {
        $location = TouristService::findOrFail($id);

        $data = $request->validate([
            'services'        => 'required|array|min:1',
            'services.*.name_ar'         => 'required|string|max:255',
            'services.*.name_en'         => 'required|string|max:255',
            'services.*.service_type_id' => 'nullable|integer|exists:service_types,id',
            'services.*.website_url'     => 'nullable|url|max:255',
            'services.*.image_file'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'services.*.image_url'       => 'nullable|url|max:1024',
        ]);

        DB::transaction(function () use ($location, $data, $request) {
            $services = $data['services'];
            
            // حفظ كل خدمة كسجل منفصل
            foreach ($services as $index => $serviceData) {
                // رفع صورة الخدمة إذا تم اختيارها
                if ($request->hasFile("services.{$index}.image_file")) {
                    $image = $request->file("services.{$index}.image_file");
                    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    
                    // إنشاء المجلد إذا لم يكن موجوداً
                    $uploadPath = public_path('images/tourist-services');
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    
                    // حفظ الصورة في مجلد public
                    $image->move($uploadPath, $imageName);
                    $serviceData['image_path'] = 'images/tourist-services/' . $imageName;
                }

                // دمج بيانات الموقع مع بيانات الخدمة
                $serviceData = array_merge([
                    'name_ar' => $location->name_ar,
                    'name_en' => $location->name_en,
                    'website_url' => $location->website_url,
                    'governorate_id' => $location->governorate_id,
                    'wilayat_id' => $location->wilayat_id,
                    'location_image_path' => $location->location_image_path,
                    'location_image_url' => $location->location_image_url,
                ], $serviceData);
                
                // إزالة image_file من البيانات قبل الحفظ
                unset($serviceData['image_file']);
                
                TouristService::create($serviceData);
            }
        });

        return redirect()->route('tourist-services.show', $location->id)
            ->with('success', 'تمت إضافة الخدمات بنجاح');
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
                $oldImagePath = public_path($service->image_path);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // إنشاء المجلد إذا لم يكن موجوداً
            $uploadPath = public_path('images/tourist-services');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // حفظ الصورة في مجلد public
            $image->move($uploadPath, $imageName);
            $data['image_path'] = 'images/tourist-services/' . $imageName;
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

