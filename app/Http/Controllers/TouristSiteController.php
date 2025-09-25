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
            'images'         => 'nullable|array',
            'images.*'       => 'required|string|max:1024',
            'image_files'    => 'nullable|array',
            'image_files.*'  => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::transaction(function () use ($data, $request) {
            $images = $data['images'] ?? [];
            $imageFiles = $request->file('image_files') ?? [];
            unset($data['images']);

            $site = TouristSite::create($data);

            $imageRows = [];

            // معالجة روابط الصور
            if (!empty($images)) {
                foreach ($images as $url) {
                    $imageRows[] = [
                        'tourist_site_id' => $site->id,
                        'image_url'       => $url,
                        'image_path'      => null,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ];
                }
            }

            // معالجة ملفات الصور
            if (!empty($imageFiles)) {
                foreach ($imageFiles as $image) {
                    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->storeAs('images/tourist-sites', $imageName, 'public');
                    
                    $imageRows[] = [
                        'tourist_site_id' => $site->id,
                        'image_url'       => null,
                        'image_path'      => $imagePath,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ];
                }
            }

            if (!empty($imageRows)) {
                TouristImage::insert($imageRows);
            }
        });

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
            'images'         => 'nullable|array',
            'images.*'       => 'required_with:images|string|max:1024',
            'add_images'         => 'nullable|array',
            'add_images.*'       => 'required_with:add_images|string|max:1024',
            'delete_image_ids'   => 'nullable|array',
            'delete_image_ids.*' => 'integer|exists:tourist_images,id',
        ]);

        DB::transaction(function () use ($site, $data) {
            // تحديث بيانات الموقع
            $site->update(collect($data)->except(['images','add_images','delete_image_ids'])->toArray());

            // استبدال كامل للصور (إن تم إرسال "images")
            if (array_key_exists('images', $data)) {
                TouristImage::where('tourist_site_id', $site->id)->delete();

                $new = $data['images'] ?? [];
                if (!empty($new)) {
                    $rows = array_map(fn($url) => [
                        'tourist_site_id' => $site->id,
                        'image_url'       => $url,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ], $new);
                    TouristImage::insert($rows);
                }
            }

            // إضافة صور جديدة (جزئي)
            if (!empty($data['add_images'] ?? [])) {
                $rows = array_map(fn($url) => [
                    'tourist_site_id' => $site->id,
                    'image_url'       => $url,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ], $data['add_images']);
                TouristImage::insert($rows);
            }

            // حذف صور محددة (جزئي)
            if (!empty($data['delete_image_ids'] ?? [])) {
                TouristImage::where('tourist_site_id', $site->id)
                    ->whereIn('id', $data['delete_image_ids'])
                    ->delete();
            }
        });

        return redirect()->route('tourist-sites.index')
            ->with('success', 'تم تحديث بيانات الموقع السياحي بنجاح');
    }

    /**
     * حذف موقع سياحي
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            TouristImage::where('tourist_site_id', $id)->delete();
            TouristSite::where('id', $id)->delete();
        });

        return redirect()->route('tourist-sites.index')
            ->with('success', 'تم حذف الموقع السياحي بنجاح');
    }
}

