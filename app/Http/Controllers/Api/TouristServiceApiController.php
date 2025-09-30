<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TouristServiceResource;
use App\Models\TouristService;
use Illuminate\Http\Request;

class TouristServiceApiController extends Controller
{
    /**
     * عرض جميع الخدمات السياحية
     */
    public function index(Request $request)
    {
        try {
            $query = TouristService::with(['serviceType', 'governorate', 'wilayat']);

            // فلترة حسب نوع الخدمة
            if ($request->filled('service_type_id')) {
                $query->where('service_type_id', $request->service_type_id);
            }

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
                case 'service_type':
                    $query->join('service_types', 'tourist_services.service_type_id', '=', 'service_types.id')
                          ->orderBy('service_types.name_ar');
                    break;
                default:
                    $query->orderByDesc('created_at');
                    break;
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
     * عرض خدمة سياحية محددة
     */
    public function show($identifier)
    {
        try {
            $service = TouristService::with(['serviceType', 'governorate', 'wilayat'])
                ->where(function($query) use ($identifier) {
                    $query->where('id', $identifier)
                          ->orWhere('slug', $identifier);
                })
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => new TouristServiceResource($service)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'الخدمة السياحية غير موجودة',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * إنشاء خدمة سياحية جديدة (Admin only)
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name_ar' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'service_type_id' => 'required|exists:service_types,id',
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
                'price_range' => 'nullable|string',
                'rating' => 'nullable|numeric|min:1|max:5',
            ]);

            $service = TouristService::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الخدمة السياحية بنجاح',
                'data' => new TouristServiceResource($service)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في إنشاء الخدمة السياحية',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث خدمة سياحية (Admin only)
     */
    public function update(Request $request, $id)
    {
        try {
            $service = TouristService::findOrFail($id);

            $validated = $request->validate([
                'name_ar' => 'sometimes|required|string|max:255',
                'name_en' => 'sometimes|required|string|max:255',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'service_type_id' => 'sometimes|required|exists:service_types,id',
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
                'price_range' => 'nullable|string',
                'rating' => 'nullable|numeric|min:1|max:5',
            ]);

            $service->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الخدمة السياحية بنجاح',
                'data' => new TouristServiceResource($service)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في تحديث الخدمة السياحية',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف خدمة سياحية (Admin only)
     */
    public function destroy($id)
    {
        try {
            $service = TouristService::findOrFail($id);
            $service->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الخدمة السياحية بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في حذف الخدمة السياحية',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
