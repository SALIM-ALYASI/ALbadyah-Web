<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TouristSiteResource;
use App\Models\TouristSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TouristSiteApiController extends Controller
{
    // بب
    /**
     * عرض جميع المواقع السياحية
     */
    public function index(Request $request)
    {
        try {
            $query = TouristSite::with(['governorate', 'wilayat', 'images']);

            // فلترة حسب المحافظة
            if ($request->filled('governorate_id')) {
                $query->where('governorate_id', $request->governorate_id);
            }

            // فلترة حسب الولاية
            if ($request->filled('wilayat_id')) {
                $query->where('wilayat_id', $request->wilayat_id);
            }

            // البحث
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name_ar', 'like', "%{$search}%")
                      ->orWhere('name_en', 'like', "%{$search}%")
                      ->orWhere('description_ar', 'like', "%{$search}%")
                      ->orWhere('description_en', 'like', "%{$search}%");
                });
            }

            // الترتيب
            $sort = $request->get('sort', 'created_at');
            switch ($sort) {
                case 'name_ar':
                    $query->orderBy('name_ar');
                    break;
                case 'name_en':
                    $query->orderBy('name_en');
                    break;
                case 'governorate':
                    $query->join('governorates', 'tourist_sites.governorate_id', '=', 'governorates.id')
                          ->orderBy('governorates.name_ar');
                    break;
                default:
                    $query->orderByDesc('created_at');
                    break;
            }

            $perPage = $request->get('per_page', 15);
            $sites = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => TouristSiteResource::collection($sites),
                'pagination' => [
                    'current_page' => $sites->currentPage(),
                    'last_page' => $sites->lastPage(),
                    'per_page' => $sites->perPage(),
                    'total' => $sites->total(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في جلب البيانات',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض موقع سياحي محدد
     */
    public function show($identifier)
    {
        try {
            $site = TouristSite::with(['governorate', 'wilayat', 'images'])
                ->where(function($query) use ($identifier) {
                    $query->where('id', $identifier)
                          ->orWhere('slug', $identifier);
                })
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => new TouristSiteResource($site)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'الموقع السياحي غير موجود',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * عرض صور موقع سياحي محدد
     */
    public function images($identifier)
    {
        try {
            $site = TouristSite::with('images')
                ->where(function($query) use ($identifier) {
                    $query->where('id', $identifier)
                          ->orWhere('slug', $identifier);
                })
                ->firstOrFail();
            
            $images = $site->images->map(function($image) use ($site) {
                return [
                    'id' => $image->id,
                    'url' => $image->image_url,
                    'alt' => $image->alt_text ?? $site->name_ar,
                    'created_at' => $image->created_at,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $images
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في جلب الصور',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * إنشاء موقع سياحي جديد (Admin only)
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name_ar' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'governorate_id' => 'required|exists:governorates,id',
                'wilayat_id' => 'required|exists:wilayats,id',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'address_ar' => 'nullable|string',
                'address_en' => 'nullable|string',
                'phone' => 'nullable|string',
                'email' => 'nullable|email',
                'website' => 'nullable|url',
                'opening_hours' => 'nullable|string',
                'entry_fee' => 'nullable|numeric',
            ]);

            $site = TouristSite::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الموقع السياحي بنجاح',
                'data' => new TouristSiteResource($site)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في إنشاء الموقع السياحي',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث موقع سياحي (Admin only)
     */
    public function update(Request $request, $id)
    {
        try {
            $site = TouristSite::findOrFail($id);

            $validated = $request->validate([
                'name_ar' => 'sometimes|required|string|max:255',
                'name_en' => 'sometimes|required|string|max:255',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'governorate_id' => 'sometimes|required|exists:governorates,id',
                'wilayat_id' => 'sometimes|required|exists:wilayats,id',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'address_ar' => 'nullable|string',
                'address_en' => 'nullable|string',
                'phone' => 'nullable|string',
                'email' => 'nullable|email',
                'website' => 'nullable|url',
                'opening_hours' => 'nullable|string',
                'entry_fee' => 'nullable|numeric',
            ]);

            $site->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الموقع السياحي بنجاح',
                'data' => new TouristSiteResource($site)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في تحديث الموقع السياحي',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف موقع سياحي (Admin only)
     */
    public function destroy($id)
    {
        try {
            $site = TouristSite::findOrFail($id);
            
            // حذف الصور المرتبطة
            foreach ($site->images as $image) {
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
                $image->delete();
            }
            
            $site->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الموقع السياحي بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في حذف الموقع السياحي',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * إضافة صور لموقع سياحي (Admin only)
     */
    public function storeImages(Request $request, $id)
    {
        try {
            $site = TouristSite::findOrFail($id);

            $request->validate([
                'images' => 'required|array|min:1|max:10',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'alt_texts' => 'nullable|array',
                'alt_texts.*' => 'nullable|string|max:255',
            ]);

            $uploadedImages = [];

            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('tourist-sites/' . $site->id, 'public');
                
                $altText = $request->alt_texts[$index] ?? $site->name_ar;
                
                $imageModel = $site->images()->create([
                    'image_path' => $path,
                    'image_url' => \App\Helpers\ImageHelper::getImageUrl($path, null),
                    'alt_text' => $altText,
                ]);

                $uploadedImages[] = [
                    'id' => $imageModel->id,
                    'url' => $imageModel->image_url,
                    'alt' => $altText,
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'تم رفع الصور بنجاح',
                'data' => $uploadedImages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في رفع الصور',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف صورة من موقع سياحي (Admin only)
     */
    public function deleteImage($id, $imageId)
    {
        try {
            $site = TouristSite::findOrFail($id);
            $image = $site->images()->findOrFail($imageId);

            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }

            $image->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الصورة بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في حذف الصورة',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
