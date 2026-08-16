<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GovernorateApiController;
use App\Http\Controllers\Api\WilayatApiController;
use App\Http\Controllers\Api\TouristSiteApiController;
use App\Http\Controllers\Api\TouristServiceApiController;
use App\Http\Controllers\Api\VisitApiController;
use App\Http\Controllers\Api\SearchApiController;
use App\Http\Controllers\Api\BadyahBotItemController;
use App\Http\Controllers\AuthController;

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

// Authentication Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');

// Public API Routes (no authentication required)
Route::prefix('v1')->group(function () {
    
    // Governorates API
    Route::get('/governorates', [GovernorateApiController::class, 'index']);
    Route::get('/governorates/hierarchy', [GovernorateApiController::class, 'hierarchy']);
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
                'total_tourist_sites' => \App\Models\TouristSite::publiclyVisible()->count(),
                'total_tourist_services' => \App\Models\TouristService::publiclyVisible()->count(),
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

/*
|--------------------------------------------------------------------------
| بوت البادية المستقل (تلجرام + OSM) — API منفصل ومحدود الصلاحية
|--------------------------------------------------------------------------
|
| توثيق مستقل عبر BADYAH_BOT_API_TOKEN فقط (لا Sanctum ولا admin.auth).
| كل عنصر يُحفظ pending + is_active=false دائمًا — لا نشر مباشر من هنا.
|
*/
Route::prefix('badyah-bot')->middleware(['badyah-bot.auth', 'throttle:20,1'])->group(function () {
    Route::post('/items', [BadyahBotItemController::class, 'store']);
    Route::get('/items', [BadyahBotItemController::class, 'index']);
    Route::get('/categories', [BadyahBotItemController::class, 'categories']);
    Route::get('/areas', [BadyahBotItemController::class, 'areas']);
    Route::get('/wilayats/{wilayat}/stats', [BadyahBotItemController::class, 'wilayatStats']);
    Route::get('/wilayats/{wilayat}/items', [BadyahBotItemController::class, 'wilayatItems']);
});
