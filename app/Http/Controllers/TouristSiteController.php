<?php

namespace App\Http\Controllers;

use App\Models\TouristSite;
use App\Models\TouristImage;
use App\Models\Governorate;
use App\Models\Wilayat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                if ($image->image_path && file_exists(public_path($image->image_path))) {
                    unlink(public_path($image->image_path));
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
            'image_files'    => 'nullable|array',
            'image_files.*'  => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_urls'     => 'nullable|array',
            'image_urls.*'   => 'required_with:image_urls|string|max:1024',
        ]);

        DB::transaction(function () use ($site, $data, $request) {
            $imageRows = [];

            // معالجة ملفات الصور
            $imageFiles = $request->file('image_files') ?? [];
            if (!empty($imageFiles)) {
                $uploadPath = public_path('images/tourist-sites');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                foreach ($imageFiles as $image) {
                    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move($uploadPath, $imageName);
                    
                    $imageRows[] = [
                        'tourist_site_id' => $site->id,
                        'image_url'       => null,
                        'image_path'      => 'images/tourist-sites/' . $imageName,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ];
                }
            }

            // معالجة روابط الصور
            $imageUrls = $data['image_urls'] ?? [];
            if (!empty($imageUrls)) {
                foreach ($imageUrls as $url) {
                    $imageRows[] = [
                        'tourist_site_id' => $site->id,
                        'image_url'       => $url,
                        'image_path'      => null,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ];
                }
            }

            if (!empty($imageRows)) {
                TouristImage::insert($imageRows);
            }
        });

        return redirect()->route('tourist-sites.show', $site->id)
            ->with('success', 'تمت إضافة الصور بنجاح');
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

