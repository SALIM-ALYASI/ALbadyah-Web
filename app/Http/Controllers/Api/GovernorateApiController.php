<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GovernorateResource;
use App\Http\Resources\WilayatResource;
use App\Http\Resources\TouristSiteResource;
use App\Http\Resources\TouristServiceResource;
use App\Models\Governorate;
use Illuminate\Http\Request;

class GovernorateApiController extends Controller
{
    /**
     * عرض جميع المحافظات
     */
    public function index(Request $request)
    {
        try {
            $query = Governorate::with(['wilayats', 'touristSites', 'touristServices'])
                ->withCount(['wilayats', 'touristSites', 'touristServices']);

            // البحث
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name_ar', 'like', "%{$search}%")
                      ->orWhere('name_en', 'like', "%{$search}%")
                      ->orWhere('description_ar', 'like', "%{$search}%");
                });
            }

            // الترتيب
            $sort = $request->get('sort', 'name_ar');
            switch ($sort) {
                case 'sites':
                    $query->orderByDesc('tourist_sites_count');
                    break;
                case 'services':
                    $query->orderByDesc('tourist_services_count');
                    break;
                case 'wilayats':
                    $query->orderByDesc('wilayats_count');
                    break;
                default:
                    $query->orderBy('name_ar');
                    break;
            }

            $perPage = $request->get('per_page', 15);
            $governorates = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => GovernorateResource::collection($governorates),
                'pagination' => [
                    'current_page' => $governorates->currentPage(),
                    'last_page' => $governorates->lastPage(),
                    'per_page' => $governorates->perPage(),
                    'total' => $governorates->total(),
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
     * عرض محافظة محددة
     */
    public function show($identifier)
    {
        try {
            $governorate = Governorate::with(['wilayats', 'touristSites', 'touristServices'])
                ->withCount(['wilayats', 'touristSites', 'touristServices'])
                ->where(function($query) use ($identifier) {
                    $query->where('id', $identifier)
                          ->orWhere('slug', $identifier);
                })
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => new GovernorateResource($governorate)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'المحافظة غير موجودة',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * عرض ولايات محافظة محددة
     */
    public function wilayats($identifier, Request $request)
    {
        try {
            $governorate = Governorate::where(function($query) use ($identifier) {
                $query->where('id', $identifier)
                      ->orWhere('slug', $identifier);
            })->firstOrFail();
            
            $query = $governorate->wilayats()->with(['touristSites', 'touristServices'])
                ->withCount(['touristSites', 'touristServices']);

            // البحث
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name_ar', 'like', "%{$search}%")
                      ->orWhere('name_en', 'like', "%{$search}%");
                });
            }

            $perPage = $request->get('per_page', 15);
            $wilayats = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => WilayatResource::collection($wilayats),
                'pagination' => [
                    'current_page' => $wilayats->currentPage(),
                    'last_page' => $wilayats->lastPage(),
                    'per_page' => $wilayats->perPage(),
                    'total' => $wilayats->total(),
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
     * عرض المواقع السياحية لمحافظة محددة
     */
    public function touristSites($identifier, Request $request)
    {
        try {
            $governorate = Governorate::where(function($query) use ($identifier) {
                $query->where('id', $identifier)
                      ->orWhere('slug', $identifier);
            })->firstOrFail();
            
            $query = $governorate->touristSites()->with(['wilayat', 'images']);

            // البحث
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name_ar', 'like', "%{$search}%")
                      ->orWhere('name_en', 'like', "%{$search}%")
                      ->orWhere('description_ar', 'like', "%{$search}%");
                });
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
     * عرض الخدمات السياحية لمحافظة محددة
     */
    public function touristServices($identifier, Request $request)
    {
        try {
            $governorate = Governorate::where(function($query) use ($identifier) {
                $query->where('id', $identifier)
                      ->orWhere('slug', $identifier);
            })->firstOrFail();
            
            $query = $governorate->touristServices()->with(['serviceType', 'wilayat']);

            // البحث
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name_ar', 'like', "%{$search}%")
                      ->orWhere('name_en', 'like', "%{$search}%");
                });
            }

            $perPage = $request->get('per_page', 15);
            $services = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => TouristServiceResource::collection($services),
                'pagination' => [
                    'current_page' => $services->currentPage(),
                    'last_page' => $services->lastPage(),
                    'per_page' => $services->perPage(),
                    'total' => $services->total(),
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
     * إنشاء محافظة جديدة (Admin only)
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name_ar' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
            ]);

            $governorate = Governorate::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء المحافظة بنجاح',
                'data' => new GovernorateResource($governorate)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في إنشاء المحافظة',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث محافظة (Admin only)
     */
    public function update(Request $request, $id)
    {
        try {
            $governorate = Governorate::findOrFail($id);

            $validated = $request->validate([
                'name_ar' => 'sometimes|required|string|max:255',
                'name_en' => 'sometimes|required|string|max:255',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
            ]);

            $governorate->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث المحافظة بنجاح',
                'data' => new GovernorateResource($governorate)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في تحديث المحافظة',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف محافظة (Admin only)
     */
    public function destroy($id)
    {
        try {
            $governorate = Governorate::findOrFail($id);
            $governorate->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف المحافظة بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في حذف المحافظة',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
