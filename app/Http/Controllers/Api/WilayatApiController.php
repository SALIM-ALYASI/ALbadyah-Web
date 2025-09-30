<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WilayatResource;
use App\Http\Resources\TouristSiteResource;
use App\Http\Resources\TouristServiceResource;
use App\Models\Wilayat;
use Illuminate\Http\Request;

class WilayatApiController extends Controller
{
    /**
     * عرض جميع الولايات
     */
    public function index(Request $request)
    {
        try {
            $query = Wilayat::with(['governorate', 'touristSites', 'touristServices'])
                ->withCount(['touristSites', 'touristServices']);

            // البحث
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name_ar', 'like', "%{$search}%")
                      ->orWhere('name_en', 'like', "%{$search}%")
                      ->orWhere('description_ar', 'like', "%{$search}%");
                });
            }

            // فلترة حسب المحافظة
            if ($request->filled('governorate_id')) {
                $query->where('governorate_id', $request->governorate_id);
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
                default:
                    $query->orderBy('name_ar');
                    break;
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
     * عرض ولاية محددة
     */
    public function show($identifier)
    {
        try {
            $wilayat = Wilayat::with(['governorate', 'touristSites', 'touristServices'])
                ->withCount(['touristSites', 'touristServices'])
                ->where(function($query) use ($identifier) {
                    $query->where('id', $identifier)
                          ->orWhere('slug', $identifier);
                })
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => new WilayatResource($wilayat)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'الولاية غير موجودة',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * عرض المواقع السياحية لولاية محددة
     */
    public function touristSites($identifier, Request $request)
    {
        try {
            $wilayat = Wilayat::where(function($query) use ($identifier) {
                $query->where('id', $identifier)
                      ->orWhere('slug', $identifier);
            })->firstOrFail();
            
            $query = $wilayat->touristSites()->with(['governorate', 'images']);

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
     * عرض الخدمات السياحية لولاية محددة
     */
    public function touristServices($identifier, Request $request)
    {
        try {
            $wilayat = Wilayat::where(function($query) use ($identifier) {
                $query->where('id', $identifier)
                      ->orWhere('slug', $identifier);
            })->firstOrFail();
            
            $query = $wilayat->touristServices()->with(['serviceType', 'governorate']);

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
     * إنشاء ولاية جديدة (Admin only)
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
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
            ]);

            $wilayat = Wilayat::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الولاية بنجاح',
                'data' => new WilayatResource($wilayat)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في إنشاء الولاية',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث ولاية (Admin only)
     */
    public function update(Request $request, $id)
    {
        try {
            $wilayat = Wilayat::findOrFail($id);

            $validated = $request->validate([
                'name_ar' => 'sometimes|required|string|max:255',
                'name_en' => 'sometimes|required|string|max:255',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'governorate_id' => 'sometimes|required|exists:governorates,id',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
            ]);

            $wilayat->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الولاية بنجاح',
                'data' => new WilayatResource($wilayat)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في تحديث الولاية',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف ولاية (Admin only)
     */
    public function destroy($id)
    {
        try {
            $wilayat = Wilayat::findOrFail($id);
            $wilayat->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الولاية بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في حذف الولاية',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
