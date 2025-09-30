<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GovernorateController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WilayatController;
use App\Http\Controllers\TouristSiteController;
use App\Http\Controllers\TouristServiceController;
use App\Http\Controllers\TourismWebsiteController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\VisitStatsController;

// الصفحة الرئيسية للموقع السياحي
Route::get('/', [TourismWebsiteController::class, 'index'])->name('tourism.index');

// روابط الزيارات
Route::post('/save-visit', [VisitController::class, 'saveVisit'])->name('visit.save');
Route::get('/visit-stats', [VisitController::class, 'getStats'])->name('visit.stats');
Route::get('/total-visits', [VisitController::class, 'getTotalVisits'])->name('visit.total');

// روابط الموقع السياحي
Route::prefix('tourism')->name('tourism.')->group(function () {
    Route::get('/search', [TourismWebsiteController::class, 'search'])->name('search');
    Route::get('/search/results', [TourismWebsiteController::class, 'searchResults'])->name('search.results');
    Route::get('/governorates', [TourismWebsiteController::class, 'governorates'])->name('governorates');
    Route::get('/governorates/{identifier}', [TourismWebsiteController::class, 'governorate'])->name('governorate');
    Route::get('/wilayats', [TourismWebsiteController::class, 'wilayats'])->name('wilayats');
    Route::get('/wilayats/{identifier}', [TourismWebsiteController::class, 'wilayat'])->name('wilayat');
    Route::get('/wilayat-details/{governorate_id}', [TourismWebsiteController::class, 'wilayatDetails'])->name('wilayat-details');
    Route::get('/tourist-sites', [TourismWebsiteController::class, 'touristSites'])->name('tourist-sites');
    Route::get('/tourist-sites/{identifier}', [TourismWebsiteController::class, 'touristSite'])->name('tourist-site');
    Route::get('/tourist-services', [TourismWebsiteController::class, 'touristServices'])->name('tourist-services');
    Route::get('/tourist-services/{identifier}', [TourismWebsiteController::class, 'touristService'])->name('tourist-service');
    Route::get('/about', [TourismWebsiteController::class, 'about'])->name('about');
});


// روابط تسجيل دخول الإدمن
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login']);
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// حماية لوحة التحكم باستخدام middleware متقدم
Route::group([
    'prefix' => 'dashboard',
    'middleware' => 'admin.auth'
    ], function () {
        Route::resource('governorates', GovernorateController::class);
        Route::resource('wilayats', WilayatController::class);
        Route::resource('tourist-sites', TouristSiteController::class);
        Route::resource('tourist-services', TouristServiceController::class);
        
        // روابط إضافية للخدمات السياحية
        Route::get('tourist-services/create/location', [TouristServiceController::class, 'createLocation'])->name('tourist-services.create-location');
        Route::post('tourist-services/store/location', [TouristServiceController::class, 'storeLocation'])->name('tourist-services.store-location');
        Route::get('tourist-services/{id}/add-services', [TouristServiceController::class, 'addServices'])->name('tourist-services.add-services');
        Route::post('tourist-services/{id}/store-services', [TouristServiceController::class, 'storeServices'])->name('tourist-services.store-services');
        
        // إدارة صور المواقع السياحية
        Route::post('tourist-sites/{id}/images', [TouristSiteController::class, 'addImages'])->name('tourist-sites.images.store');
        Route::delete('tourist-sites/{id}/images/{imageId}', [TouristSiteController::class, 'deleteImage'])->name('tourist-sites.images.destroy');
        
        // صفحة عرض جميع البيانات
        Route::get('/data-viewer', [TouristServiceController::class, 'dataViewer'])->name('data-viewer.index');
        
        // إحصائيات الزيارات
        Route::get('/visit-stats', [VisitStatsController::class, 'index'])->name('visit-stats.index');
    });

// روابط المحافظات بدون حماية (للاستخدام العام إذا احتجت)
// Route::resource('governorates', GovernorateController::class);
