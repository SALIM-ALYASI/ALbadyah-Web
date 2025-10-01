<?php

namespace App\Http\Controllers;

use App\Models\TouristSiteNew;
use App\Models\TouristImageNew;
use App\Models\Governorate;
use App\Models\Wilayat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TouristSiteNewController extends Controller
{
    /**
     * عرض قائمة المواقع السياحية
     */
    public function index(Request $request)
    {
        try {
            $query = TouristSiteNew::with(['images', 'governorate', 'wilayat']);

            // البحث
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name_ar', 'like', "%{$search}%")
                      ->orWhere('name_en', 'like', "%{$search}%")
                      ->orWhere('description_ar', 'like', "%{$search}%")
                      ->orWhere('description_en', 'like', "%{$search}%");
                });
            }

            // فلترة حسب المحافظة
            if ($request->filled('governorate_id')) {
                $query->where('governorate_id', $request->governorate_id);
            }

            // فلترة حسب الولاية
            if ($request->filled('wilayat_id')) {
                $query->where('wilayat_id', $request->wilayat_id);
            }

            // فلترة حسب الحالة
            if ($request->filled('status')) {
                $query->where('is_active', $request->status === 'active');
            }

            $touristSites = $query->latest()->paginate(15);
            $governorates = Governorate::all();
            $wilayats = Wilayat::all();

            return view('admin.tourist-sites-new.index', compact('touristSites', 'governorates', 'wilayats'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching tourist sites: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ في جلب البيانات');
        }
    }

    /**
     * عرض نموذج إضافة موقع سياحي جديد
     */
    public function create()
    {
        try {
            $governorates = Governorate::all();
            $wilayats = Wilayat::all();
            return view('admin.tourist-sites-new.create', compact('governorates', 'wilayats'));
        } catch (\Exception $e) {
            Log::error('Error loading create form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ في تحميل النموذج');
        }
    }

    /**
     * حفظ موقع سياحي جديد
     */
    public function store(Request $request)
    {
        Log::info('=== TouristSiteNew Store Method Called ===');
        Log::info('Request Data:', $request->all());
        Log::info('Request Method:', [$request->method()]);
        Log::info('Request URL:', [$request->fullUrl()]);
        
        try {
            $data = $request->validate([
                'name_ar' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'description_ar' => 'required|string',
                'description_en' => 'required|string',
                'location' => 'nullable|string|max:255',
                'governorate_id' => 'nullable|integer|exists:governorates,id',
                'wilayat_id' => 'nullable|integer|exists:wilayats,id',
                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // تعيين القيم الافتراضية للحقول المحذوفة
            $data['is_active'] = true; // افتراضياً الموقع نشط

            DB::transaction(function () use ($data, $request) {
                // إنشاء slug فريد
                $baseSlug = Str::slug($data['name_en'] ?: $data['name_ar']);
                $slug = $baseSlug;
                $counter = 1;
                
                while (TouristSiteNew::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                $data['slug'] = $slug;

                // معالجة الصورة المميزة
                if ($request->hasFile('featured_image')) {
                    $image = $request->file('featured_image');
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('tourist-sites/featured', $filename, 'public');
                    $data['featured_image'] = $path;
                }

                $site = TouristSiteNew::create($data);
                
                // معالجة الصور الإضافية
                if ($request->hasFile('images')) {
                    $this->handleImages($site->id, $request->file('images'));
                }
            });

            Log::info('=== Tourist Site Created Successfully ===');
            return redirect()->route('tourist-sites-new.index')
                ->with('success', 'تمت إضافة الموقع السياحي بنجاح!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating tourist site: ' . $e->getMessage());
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
        try {
            $touristSite = TouristSiteNew::with(['images' => function($query) {
                $query->ordered();
            }, 'governorate', 'wilayat'])->findOrFail($id);
            
            return view('admin.tourist-sites-new.show', compact('touristSite'));
        } catch (\Exception $e) {
            Log::error('Error fetching tourist site: ' . $e->getMessage());
            return redirect()->back()->with('error', 'الموقع السياحي غير موجود');
        }
    }

    /**
     * عرض نموذج تعديل موقع سياحي
     */
    public function edit($id)
    {
        try {
            $touristSite = TouristSiteNew::with(['images' => function($query) {
                $query->ordered();
            }])->findOrFail($id);
            $governorates = Governorate::all();
            $wilayats = Wilayat::all();
            
            return view('admin.tourist-sites-new.edit', compact('touristSite', 'governorates', 'wilayats'));
        } catch (\Exception $e) {
            Log::error('Error loading edit form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'الموقع السياحي غير موجود');
        }
    }

    /**
     * تحديث موقع سياحي
     */
    public function update(Request $request, $id)
    {
        try {
            $site = TouristSiteNew::findOrFail($id);

            $data = $request->validate([
                'name_ar' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'description_ar' => 'required|string',
                'description_en' => 'required|string',
                'location' => 'nullable|string|max:255',
                'governorate_id' => 'nullable|integer|exists:governorates,id',
                'wilayat_id' => 'nullable|integer|exists:wilayats,id',
                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // تعيين القيم الافتراضية للحقول المحذوفة
            $data['is_active'] = $request->has('is_active') ? true : false;

            DB::transaction(function () use ($site, $data, $request) {
                // تحديث slug إذا تغير الاسم
                if ($site->name_ar !== $data['name_ar'] || $site->name_en !== $data['name_en']) {
                    $baseSlug = Str::slug($data['name_en'] ?: $data['name_ar']);
                    $slug = $baseSlug;
                    $counter = 1;
                    
                    while (TouristSiteNew::where('slug', $slug)->where('id', '!=', $site->id)->exists()) {
                        $slug = $baseSlug . '-' . $counter;
                        $counter++;
                    }
                    $data['slug'] = $slug;
                }

                // معالجة الصورة المميزة الجديدة
                if ($request->hasFile('featured_image')) {
                    // حذف الصورة القديمة
                    if ($site->featured_image && Storage::disk('public')->exists($site->featured_image)) {
                        Storage::disk('public')->delete($site->featured_image);
                    }
                    
                    $image = $request->file('featured_image');
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('tourist-sites/featured', $filename, 'public');
                    $data['featured_image'] = $path;
                }

                $site->update($data);
                
                // معالجة الصور الإضافية
                if ($request->hasFile('images')) {
                    $this->handleImages($site->id, $request->file('images'));
                }
            });

            return redirect()->route('tourist-sites-new.index')
                ->with('success', 'تم تحديث بيانات الموقع السياحي بنجاح');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating tourist site: ' . $e->getMessage());
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
            $site = TouristSiteNew::findOrFail($id);
            
            DB::transaction(function () use ($site) {
                // حذف الصورة المميزة
                if ($site->featured_image && Storage::disk('public')->exists($site->featured_image)) {
                    Storage::disk('public')->delete($site->featured_image);
                }
                
                // حذف جميع صور الموقع
                foreach ($site->images as $image) {
                    if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                        Storage::disk('public')->delete($image->image_path);
                    }
                }
                
                // حذف الموقع السياحي (سيحذف الصور تلقائياً بسبب cascade)
                $site->delete();
            });

            return redirect()->route('tourist-sites-new.index')
                ->with('success', 'تم حذف الموقع السياحي بنجاح');
                
        } catch (\Exception $e) {
            Log::error('Error deleting tourist site: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف الموقع السياحي: ' . $e->getMessage());
        }
    }

    /**
     * حذف صورة من الموقع السياحي
     */
    public function deleteImage($id, $imageId)
    {
        try {
            $site = TouristSiteNew::findOrFail($id);
            $image = TouristImageNew::where('tourist_site_id', $id)->findOrFail($imageId);

            DB::transaction(function () use ($image) {
                // حذف ملف الصورة
                if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
                
                $image->delete();
            });

            return redirect()->route('tourist-sites-new.show', $site->id)
                ->with('success', 'تم حذف الصورة بنجاح');
                
        } catch (\Exception $e) {
            Log::error('Error deleting image: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف الصورة: ' . $e->getMessage());
        }
    }

    /**
     * تحديث ترتيب الصور
     */
    public function updateImageOrder(Request $request, $id)
    {
        try {
            $request->validate([
                'images' => 'required|array',
                'images.*' => 'integer|exists:tourist_image_news,id',
            ]);

            foreach ($request->images as $index => $imageId) {
                TouristImageNew::where('id', $imageId)
                    ->where('tourist_site_id', $id)
                    ->update(['sort_order' => $index + 1]);
            }

            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            Log::error('Error updating image order: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ في تحديث ترتيب الصور'], 500);
        }
    }

    /**
     * معالجة رفع الصور
     */
    private function handleImages($touristSiteId, $images)
    {
        foreach ($images as $image) {
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('tourist-sites/images', $filename, 'public');
            
            TouristImageNew::create([
                'tourist_site_id' => $touristSiteId,
                'image_path' => $path,
                'image_url' => \App\Helpers\ImageHelper::getImageUrl($path, null),
                'alt_text_ar' => $image->getClientOriginalName(),
                'alt_text_en' => $image->getClientOriginalName(),
            ]);
        }
    }

    /**
     * حذف صورة من الموقع السياحي
     */
    public function destroyImage($touristSiteId, $imageId)
    {
        $touristSite = TouristSiteNew::findOrFail($touristSiteId);
        $image = TouristImageNew::where('id', $imageId)
                               ->where('tourist_site_id', $touristSiteId)
                               ->firstOrFail();

        // حذف الصورة من التخزين
        if ($image->image_path && \Storage::disk('public')->exists($image->image_path)) {
            \Storage::disk('public')->delete($image->image_path);
        }

        // حذف السجل من قاعدة البيانات
        $image->delete();

        return redirect()->back()->with('success', 'تم حذف الصورة بنجاح');
    }

    /**
     * رفع صور جديدة للموقع السياحي
     */
    public function storeImages(Request $request, $touristSiteId)
    {
        $touristSite = TouristSiteNew::findOrFail($touristSiteId);
        
        $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $uploadedCount = 0;
        $errors = [];

        foreach ($request->file('images') as $image) {
            try {
                $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('tourist-sites/images', $filename, 'public');
                
                // تحديد ترتيب الصورة
                $maxOrder = TouristImageNew::where('tourist_site_id', $touristSiteId)->max('sort_order') ?? 0;
                
                TouristImageNew::create([
                    'tourist_site_id' => $touristSiteId,
                    'image_path' => $path,
                    'image_url' => \App\Helpers\ImageHelper::getImageUrl($path, null),
                    'alt_text_ar' => $image->getClientOriginalName(),
                    'alt_text_en' => $image->getClientOriginalName(),
                    'sort_order' => $maxOrder + 1,
                ]);
                
                $uploadedCount++;
            } catch (\Exception $e) {
                $errors[] = $image->getClientOriginalName() . ': ' . $e->getMessage();
            }
        }

        if ($uploadedCount > 0) {
            $message = "تم رفع {$uploadedCount} صورة بنجاح";
            if (!empty($errors)) {
                $message .= ". أخطاء: " . implode(', ', $errors);
            }
            return redirect()->back()->with('success', $message);
        } else {
            return redirect()->back()->with('error', 'فشل في رفع الصور: ' . implode(', ', $errors));
        }
    }
}