<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TouristSiteResource;
use App\Http\Resources\TouristServiceResource;
use App\Models\TouristSite;
use App\Models\TouristService;
use Illuminate\Http\Request;

class SearchApiController extends Controller
{
    /**
     * البحث الشامل في المواقع والخدمات السياحية
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('q', '');
            $type = $request->get('type', 'all'); // all, sites, services
            $governorate_id = $request->get('governorate_id');
            $wilayat_id = $request->get('wilayat_id');
            $perPage = $request->get('per_page', 15);

            $results = [
                'sites' => collect(),
                'services' => collect(),
                'total_results' => 0
            ];

            if ($query) {
                // البحث في المواقع السياحية
                if ($type === 'all' || $type === 'sites') {
                    $sitesQuery = TouristSite::with(['governorate', 'wilayat', 'images'])
                        ->where(function ($q) use ($query) {
                            $q->where('name_ar', 'like', "%$query%")
                               ->orWhere('name_en', 'like', "%$query%")
                               ->orWhere('description_ar', 'like', "%$query%")
                               ->orWhere('description_en', 'like', "%$query%");
                        });

                    if ($governorate_id) {
                        $sitesQuery->where('governorate_id', $governorate_id);
                    }

                    if ($wilayat_id) {
                        $sitesQuery->where('wilayat_id', $wilayat_id);
                    }

                    $sites = $sitesQuery->paginate($perPage);
                    $results['sites'] = TouristSiteResource::collection($sites);
                }

                // البحث في الخدمات السياحية
                if ($type === 'all' || $type === 'services') {
                    $servicesQuery = TouristService::with(['serviceType', 'governorate', 'wilayat'])
                        ->where(function ($q) use ($query) {
                            $q->where('name_ar', 'like', "%$query%")
                               ->orWhere('name_en', 'like', "%$query%")
                               ->orWhere('description_ar', 'like', "%$query%")
                               ->orWhere('description_en', 'like', "%$query%");
                        });

                    if ($governorate_id) {
                        $servicesQuery->where('governorate_id', $governorate_id);
                    }

                    if ($wilayat_id) {
                        $servicesQuery->where('wilayat_id', $wilayat_id);
                    }

                    $services = $servicesQuery->paginate($perPage);
                    $results['services'] = TouristServiceResource::collection($services);
                }

                $results['total_results'] = $results['sites']->count() + $results['services']->count();
            }

            return response()->json([
                'success' => true,
                'data' => $results,
                'query' => $query,
                'filters' => [
                    'type' => $type,
                    'governorate_id' => $governorate_id,
                    'wilayat_id' => $wilayat_id,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في البحث',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * البحث في المواقع السياحية فقط
     */
    public function searchSites(Request $request)
    {
        try {
            $query = $request->get('q', '');
            $governorate_id = $request->get('governorate_id');
            $wilayat_id = $request->get('wilayat_id');
            $perPage = $request->get('per_page', 15);

            $sitesQuery = TouristSite::with(['governorate', 'wilayat', 'images']);

            if ($query) {
                $sitesQuery->where(function ($q) use ($query) {
                    $q->where('name_ar', 'like', "%$query%")
                       ->orWhere('name_en', 'like', "%$query%")
                       ->orWhere('description_ar', 'like', "%$query%")
                       ->orWhere('description_en', 'like', "%$query%");
                });
            }

            if ($governorate_id) {
                $sitesQuery->where('governorate_id', $governorate_id);
            }

            if ($wilayat_id) {
                $sitesQuery->where('wilayat_id', $wilayat_id);
            }

            $sites = $sitesQuery->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => TouristSiteResource::collection($sites),
                'pagination' => [
                    'current_page' => $sites->currentPage(),
                    'last_page' => $sites->lastPage(),
                    'per_page' => $sites->perPage(),
                    'total' => $sites->total(),
                ],
                'query' => $query,
                'filters' => [
                    'governorate_id' => $governorate_id,
                    'wilayat_id' => $wilayat_id,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في البحث',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * البحث في الخدمات السياحية فقط
     */
    public function searchServices(Request $request)
    {
        try {
            $query = $request->get('q', '');
            $service_type_id = $request->get('service_type_id');
            $governorate_id = $request->get('governorate_id');
            $wilayat_id = $request->get('wilayat_id');
            $perPage = $request->get('per_page', 15);

            $servicesQuery = TouristService::with(['serviceType', 'governorate', 'wilayat']);

            if ($query) {
                $servicesQuery->where(function ($q) use ($query) {
                    $q->where('name_ar', 'like', "%$query%")
                       ->orWhere('name_en', 'like', "%$query%")
                       ->orWhere('description_ar', 'like', "%$query%")
                       ->orWhere('description_en', 'like', "%$query%");
                });
            }

            if ($service_type_id) {
                $servicesQuery->where('service_type_id', $service_type_id);
            }

            if ($governorate_id) {
                $servicesQuery->where('governorate_id', $governorate_id);
            }

            if ($wilayat_id) {
                $servicesQuery->where('wilayat_id', $wilayat_id);
            }

            $services = $servicesQuery->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => TouristServiceResource::collection($services),
                'pagination' => [
                    'current_page' => $services->currentPage(),
                    'last_page' => $services->lastPage(),
                    'per_page' => $services->perPage(),
                    'total' => $services->total(),
                ],
                'query' => $query,
                'filters' => [
                    'service_type_id' => $service_type_id,
                    'governorate_id' => $governorate_id,
                    'wilayat_id' => $wilayat_id,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في البحث',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
