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
    /**
     * عرض قائمة المواقع السياحية
     */
    public function index()
    {
        $touristSites = TouristSite::with(['images', 'governorate', 'wilayat'])->latest()->get();
        return view('tourist-sites.index', compact('touristSites'));
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
            ]);

            $site = TouristSite::create($data);

            return redirect()->route('tourist-sites.show', $site->id)
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
}

