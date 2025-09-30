<?php

namespace App\Http\Controllers;

use App\Models\TouristSite;
use App\Models\TouristImage;
use App\Models\Governorate;
use App\Models\Wilayat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

        return redirect()->route('tourist-sites.index')
            ->with('success', 'تمت إضافة الموقع السياحي بنجاح');
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
        $site = TouristSite::findOrFail($id);

        $data = $request->validate([
            'name_ar'        => 'sometimes|string|max:255',
            'name_en'        => 'sometimes|string|max:255',
            'description_ar' => 'sometimes|string',
            'description_en' => 'sometimes|string',
            'location'       => 'nullable|string|max:255',
            'website_url'    => 'nullable|url|max:255',
            'governorate_id' => 'nullable|integer|exists:governorates,id',
            'wilayat_id'     => 'nullable|integer|exists:wilayats,id',
        ]);

        $site->update($data);

        return redirect()->route('tourist-sites.index')
            ->with('success', 'تم تحديث بيانات الموقع السياحي بنجاح');
    }

    /**
     * حذف موقع سياحي
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            // حذف ملفات الصور من الخادم قبل حذف السجلات
            $images = TouristImage::where('tourist_site_id', $id)->get();
            foreach ($images as $image) {
                if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
            }
            
            TouristImage::where('tourist_site_id', $id)->delete();
            TouristSite::where('id', $id)->delete();
        });

        return redirect()->route('tourist-sites.index')
            ->with('success', 'تم حذف الموقع السياحي بنجاح');
    }

    /**
     * إضافة صور للموقع السياحي
     */
    public function addImages(Request $request, $id)
    {
        $site = TouristSite::findOrFail($id);

        $data = $request->validate([
            'image_files'    => 'required|array|min:1',
            'image_files.*'  => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::transaction(function () use ($site, $data, $request) {
                $imageRows = [];

                // معالجة ملفات الصور المحلية فقط
                $imageFiles = $request->file('image_files');
                foreach ($imageFiles as $image) {
                    // حفظ الصورة في مجلد public مباشرة
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $imagePath = 'images/tourist-sites/' . $filename;
                    $image->move(public_path('images/tourist-sites/'), $filename);
                    
                    $imageUrl = asset($imagePath);
                    
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
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حفظ الصور: ' . $e->getMessage());
        }

        return redirect()->route('tourist-sites.show', $site->id)
            ->with('success', 'تمت إضافة الصور المحلية بنجاح');
    }

    /**
     * حذف صورة من الموقع السياحي
     */
    public function deleteImage($id, $imageId)
    {
        $site = TouristSite::findOrFail($id);
        $image = TouristImage::where('tourist_site_id', $id)->findOrFail($imageId);

        DB::transaction(function () use ($image) {
            // حذف ملف الصورة من الخادم
            if ($image->image_path && file_exists(public_path($image->image_path))) {
                unlink(public_path($image->image_path));
            }
            
            $image->delete();
        });

        return redirect()->route('tourist-sites.show', $site->id)
            ->with('success', 'تم حذف الصورة بنجاح');
    }
}

