<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GovernorateApiController;
use App\Http\Controllers\Api\WilayatApiController;
use App\Http\Controllers\Api\TouristSiteApiController;
use App\Http\Controllers\Api\TouristServiceApiController;
use App\Http\Controllers\Api\VisitApiController;
use App\Http\Controllers\Api\SearchApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API Routes (no authentication required)
Route::prefix('v1')->group(function () {
    
    // Governorates API
    Route::get('/governorates', [GovernorateApiController::class, 'index']);
    Route::get('/governorates/{identifier}', [GovernorateApiController::class, 'show']);
    Route::get('/governorates/{identifier}/wilayats', [GovernorateApiController::class, 'wilayats']);
    Route::get('/governorates/{identifier}/tourist-sites', [GovernorateApiController::class, 'touristSites']);
    Route::get('/governorates/{identifier}/tourist-services', [GovernorateApiController::class, 'touristServices']);
    
    // Wilayats API
    Route::get('/wilayats', [WilayatApiController::class, 'index']);
    Route::get('/wilayats/{identifier}', [WilayatApiController::class, 'show']);
    Route::get('/wilayats/{identifier}/tourist-sites', [WilayatApiController::class, 'touristSites']);
    Route::get('/wilayats/{identifier}/tourist-services', [WilayatApiController::class, 'touristServices']);
    
    // Tourist Sites API
    Route::get('/tourist-sites', [TouristSiteApiController::class, 'index']);
    Route::get('/tourist-sites/{identifier}', [TouristSiteApiController::class, 'show']);
    Route::get('/tourist-sites/{identifier}/images', [TouristSiteApiController::class, 'images']);
    
    // Tourist Services API
    Route::get('/tourist-services', [TouristServiceApiController::class, 'index']);
        Route::get('/tourist-services/{identifier}', [TouristServiceApiController::class, 'show']);
    
    // Search API
    Route::get('/search', [SearchApiController::class, 'search']);
    Route::get('/search/sites', [SearchApiController::class, 'searchSites']);
    Route::get('/search/services', [SearchApiController::class, 'searchServices']);
    
    // Visit Statistics API
    Route::post('/visits', [VisitApiController::class, 'store']);
    Route::get('/visits/stats', [VisitApiController::class, 'stats']);
    Route::get('/visits/total', [VisitApiController::class, 'total']);
    
    // General Statistics API
    Route::get('/stats', function () {
        return response()->json([
            'success' => true,
            'data' => [
                'total_governorates' => \App\Models\Governorate::count(),
                'total_wilayats' => \App\Models\Wilayat::count(),
                'total_tourist_sites' => \App\Models\TouristSite::count(),
                'total_tourist_services' => \App\Models\TouristService::count(),
            ]
        ]);
    });
});

// Protected API Routes (require authentication)
Route::prefix('v1/admin')->middleware(['auth:sanctum'])->group(function () {
    
    // Admin Governorates Management
    Route::apiResource('governorates', GovernorateApiController::class)->except(['index', 'show']);
    
    // Admin Wilayats Management
    Route::apiResource('wilayats', WilayatApiController::class)->except(['index', 'show']);
    
    // Admin Tourist Sites Management
    Route::apiResource('tourist-sites', TouristSiteApiController::class)->except(['index', 'show']);
    Route::post('/tourist-sites/{id}/images', [TouristSiteApiController::class, 'storeImages']);
    Route::delete('/tourist-sites/{id}/images/{imageId}', [TouristSiteApiController::class, 'deleteImage']);
    
    // Admin Tourist Services Management
    Route::apiResource('tourist-services', TouristServiceApiController::class)->except(['index', 'show']);
    
    // Admin Visit Statistics
    Route::get('/visits/stats', [VisitApiController::class, 'adminStats']);
    Route::get('/visits/export', [VisitApiController::class, 'export']);
});
