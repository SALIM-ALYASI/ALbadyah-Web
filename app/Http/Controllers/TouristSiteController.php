<?php

namespace App\Http\Controllers;

use App\Models\TouristSite;
use App\Models\TouristImage;
use App\Models\Governorate;
use App\Models\Wilayat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TouristSiteController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
    }
    /**
     * عرض قائمة المواقع السياحية
     */
    public function index(Request $request)
    {
        try {
            $query = TouristSite::with(['images' => function($query) {
                $query->ordered()->take(1); // تحميل أول صورة فقط للأداء
            }, 'governorate', 'wilayat']);

            // البحث
            if ($request->filled('search')) {
                $search = trim($request->search);
                if (!empty($search)) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name_ar', 'like', "%{$search}%")
                          ->orWhere('name_en', 'like', "%{$search}%")
                          ->orWhere('description_ar', 'like', "%{$search}%")
                          ->orWhere('description_en', 'like', "%{$search}%");
                    });
                }
            }

            // فلترة حسب المحافظة
            if ($request->filled('governorate_id') && is_numeric($request->governorate_id)) {
                $query->where('governorate_id', $request->governorate_id);
            }

            // فلترة حسب الولاية
            if ($request->filled('wilayat_id') && is_numeric($request->wilayat_id)) {
                $query->where('wilayat_id', $request->wilayat_id);
            }

            // فلترة حسب الحالة
            if ($request->filled('status')) {
                $query->where('is_active', $request->status === 'active');
            }

            // الحصول على النتائج مع الترقيم
            $touristSites = $query->latest('created_at')->paginate(15);
            
            // تحميل بيانات الفلاتر
            $governorates = Governorate::orderBy('name_ar')->get();
            $wilayats = Wilayat::orderBy('name_ar')->get();

            // إضافة معلومات إضافية للـ View
            $totalSites = TouristSite::count();
            $activeSites = TouristSite::where('is_active', true)->count();

            return view('tourist-sites.index', compact(
                'touristSites', 
                'governorates', 
                'wilayats',
                'totalSites',
                'activeSites'
            ));
            
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error in tourist sites index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ في قاعدة البيانات. يرجى المحاولة مرة أخرى.');
        } catch (\Exception $e) {
            Log::error('Error fetching tourist sites: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'حدث خطأ غير متوقع في جلب البيانات. يرجى التحقق من سجلات النظام.');
        }
    }

    /**
     * عرض نموذج إضافة موقع سياحي جديد
     */
    public function create()
    {
        $governorates = Governorate::all();
        $wilayats = Wilayat::all();
        return view('tourist-sites.create', compact('governorates', 'wilayats'));
    }

    /**
     * حفظ موقع سياحي جديد
     */
    public function store(Request $request)
    {
        // Debug: تسجيل البيانات المرسلة
        Log::info('TouristSite Store Method Called');
        Log::info('Request Data:', $request->all());
        Log::info('Request Method:', [$request->method()]);
        Log::info('Request URL:', [$request->fullUrl()]);
        
        try {
            $data = $request->validate([
                'name_ar'        => 'required|string|max:255',
                'name_en'        => 'required|string|max:255',
                'description_ar' => 'required|string',
                'description_en' => 'required|string',
                'location'       => 'nullable|string|max:255',
                'website_url'    => 'nullable|url|max:255',
                'governorate_id' => 'nullable|integer|exists:governorates,id',
                'wilayat_id'     => 'nullable|integer|exists:wilayats,id',
                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // تعيين القيم الافتراضية
            $data['is_active'] = $request->has('is_active') ? true : false;

            DB::transaction(function () use ($data, $request) {
                // معالجة الصورة المميزة
                if ($request->hasFile('featured_image')) {
                    $image = $request->file('featured_image');
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('tourist-sites/featured', $filename, 'public');
                    $data['featured_image'] = $path;
                }

                $site = TouristSite::create($data);
                
                // معالجة الصور الإضافية
                if ($request->hasFile('images')) {
                    $this->handleImages($site->id, $request->file('images'));
                }
            });

            return redirect()->route('tourist-sites.index')
                ->with('success', 'تمت إضافة الموقع السياحي بنجاح');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating tourist site: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حفظ الموقع السياحي: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * عرض موقع سياحي محدد
     */
    public function show($id)
    {
        $touristSite = TouristSite::with(['images', 'governorate', 'wilayat'])->findOrFail($id);
        return view('tourist-sites.show', compact('touristSite'));
    }

    /**
     * عرض نموذج تعديل موقع سياحي
     */
    public function edit($id)
    {
        $touristSite = TouristSite::with('images')->findOrFail($id);
        $governorates = Governorate::all();
        $wilayats = Wilayat::all();
        return view('tourist-sites.edit', compact('touristSite', 'governorates', 'wilayats'));
    }

    /**
     * تحديث موقع سياحي
     */
    public function update(Request $request, $id)
    {
        try {
            $site = TouristSite::findOrFail($id);

            $data = $request->validate([
                'name_ar'        => 'required|string|max:255',
                'name_en'        => 'required|string|max:255',
                'description_ar' => 'required|string',
                'description_en' => 'required|string',
                'location'       => 'nullable|string|max:255',
                'website_url'    => 'nullable|url|max:255',
                'governorate_id' => 'nullable|integer|exists:governorates,id',
                'wilayat_id'     => 'nullable|integer|exists:wilayats,id',
            ]);

            $site->update($data);

            return redirect()->route('tourist-sites.index')
                ->with('success', 'تم تحديث بيانات الموقع السياحي بنجاح');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث الموقع السياحي: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * حذف موقع سياحي
     */
    public function destroy($id)
    {
        try {
            // التحقق من وجود الموقع
            $site = TouristSite::findOrFail($id);
            
            DB::transaction(function () use ($id, $site) {
                // حذف ملفات الصور من الخادم قبل حذف السجلات
                $images = TouristImage::where('tourist_site_id', $id)->get();
                foreach ($images as $image) {
                    // حذف من storage
                    if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                        Storage::disk('public')->delete($image->image_path);
                    }
                    // حذف الصور المحلية أيضاً
                    if ($image->image_path && file_exists(public_path($image->image_path))) {
                        unlink(public_path($image->image_path));
                    }
                }
                
                // حذف سجلات الصور من قاعدة البيانات
                TouristImage::where('tourist_site_id', $id)->delete();
                
                // حذف الموقع السياحي
                $site->delete();
            });

            return redirect()->route('tourist-sites.index')
                ->with('success', 'تم حذف الموقع السياحي بنجاح');
                
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('tourist-sites.index')
                ->with('error', 'الموقع السياحي غير موجود');
        } catch (\Exception $e) {
            Log::error('Error deleting tourist site: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف الموقع السياحي: ' . $e->getMessage());
        }
    }

    /**
     * إضافة صور للموقع السياحي
     */
    public function addImages(Request $request, $id)
    {
        try {
            $site = TouristSite::findOrFail($id);

            $data = $request->validate([
                'image_files'    => 'required|array|min:1',
                'image_files.*'  => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            DB::transaction(function () use ($site, $data, $request) {
                $imageRows = [];

                // إنشاء مجلد الصور إذا لم يكن موجوداً
                $uploadPath = public_path('images/tourist-sites/');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                // معالجة ملفات الصور المحلية فقط
                $imageFiles = $request->file('image_files');
                foreach ($imageFiles as $image) {
                    // حفظ الصورة في مجلد public مباشرة
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $imagePath = 'images/tourist-sites/' . $filename;
                    $image->move($uploadPath, $filename);
                    
                    $imageUrl = \App\Helpers\ImageHelper::getImageUrl($imagePath, null);
                    
                    $imageRows[] = [
                        'tourist_site_id' => $site->id,
                        'image_url'       => $imageUrl,
                        'image_path'      => $imagePath,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ];
                }

                if (!empty($imageRows)) {
                    TouristImage::insert($imageRows);
                } else {
                    throw new \Exception('لم يتم إرسال أي صور للحفظ');
                }
            });

            return redirect()->route('tourist-sites.show', $site->id)
                ->with('success', 'تمت إضافة الصور بنجاح');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حفظ الصور: ' . $e->getMessage());
        }
    }

    /**
     * حذف صورة من الموقع السياحي
     */
    public function deleteImage($id, $imageId)
    {
        try {
            $site = TouristSite::findOrFail($id);
            $image = TouristImage::where('tourist_site_id', $id)->findOrFail($imageId);

            DB::transaction(function () use ($image) {
                // حذف ملف الصورة من الخادم
                if ($image->image_path && file_exists(public_path($image->image_path))) {
                    unlink(public_path($image->image_path));
                }
                
                // حذف من storage أيضاً إذا كان موجوداً
                if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
                
                $image->delete();
            });

            return redirect()->route('tourist-sites.show', $site->id)
                ->with('success', 'تم حذف الصورة بنجاح');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف الصورة: ' . $e->getMessage());
        }
    }

    /**
     * معالجة رفع الصور
     */
    private function handleImages($touristSiteId, $images)
    {
        foreach ($images as $index => $image) {
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('tourist-sites/images', $filename, 'public');
            
            // تحديد ترتيب الصورة
            $maxOrder = TouristImage::where('tourist_site_id', $touristSiteId)->max('sort_order') ?? 0;
            
            TouristImage::create([
                'tourist_site_id' => $touristSiteId,
                'image_path' => $path,
                'image_url' => \App\Helpers\ImageHelper::getImageUrl($path, null),
                'alt_text_ar' => $image->getClientOriginalName(),
                'alt_text_en' => $image->getClientOriginalName(),
                'sort_order' => $maxOrder + $index + 1,
                'is_featured' => $index === 0, // أول صورة تكون مميزة
            ]);
        }
    }
}

