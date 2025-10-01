<?php
/**
 * ملف تشخيص Laravel - البادية
 * يمكن تشغيله عبر: php debug_laravel.php
 */

require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

try {
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "=== تشخيص Laravel - البادية ===\n\n";
    
    // 1. فحص الإعدادات الأساسية
    echo "1. الإعدادات الأساسية:\n";
    echo "   APP_ENV: " . env('APP_ENV', 'غير محدد') . "\n";
    echo "   APP_DEBUG: " . (env('APP_DEBUG') ? 'true' : 'false') . "\n";
    echo "   APP_URL: " . env('APP_URL', 'غير محدد') . "\n";
    echo "   ADMIN_SECRET: " . (env('ADMIN_SECRET') ? 'محدد' : 'غير محدد') . "\n\n";
    
    // 2. فحص قاعدة البيانات
    echo "2. قاعدة البيانات:\n";
    try {
        DB::connection()->getPdo();
        echo "   ✅ الاتصال بقاعدة البيانات ناجح\n";
        
        // فحص الجداول المهمة
        $tables = [
            'tourist_site_news' => 'المواقع السياحية الجديدة',
            'tourist_image_news' => 'صور المواقع السياحية الجديدة',
            'users' => 'المستخدمين',
            'governorates' => 'المحافظات',
            'wilayats' => 'الولايات'
        ];
        
        foreach ($tables as $table => $description) {
            try {
                $count = DB::table($table)->count();
                echo "   ✅ جدول $table ($description): $count سجل\n";
            } catch (Exception $e) {
                echo "   ❌ جدول $table ($description): خطأ - " . $e->getMessage() . "\n";
            }
        }
        
    } catch (Exception $e) {
        echo "   ❌ خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage() . "\n";
    }
    echo "\n";
    
    // 3. فحص Controllers
    echo "3. Controllers:\n";
    $controllers = [
        'App\Http\Controllers\TouristSiteNewController' => 'المواقع السياحية الجديدة',
        'App\Http\Controllers\AdminController' => 'لوحة التحكم',
        'App\Http\Controllers\GovernorateController' => 'المحافظات'
    ];
    
    foreach ($controllers as $controller => $description) {
        if (class_exists($controller)) {
            echo "   ✅ $controller ($description): موجود\n";
        } else {
            echo "   ❌ $controller ($description): غير موجود\n";
        }
    }
    echo "\n";
    
    // 4. فحص Routes
    echo "4. Routes:\n";
    try {
        $routes = Route::getRoutes();
        $touristSitesNewRoutes = [];
        
        foreach ($routes as $route) {
            if (strpos($route->uri(), 'tourist-sites-new') !== false) {
                $touristSitesNewRoutes[] = [
                    'uri' => $route->uri(),
                    'methods' => implode('|', $route->methods()),
                    'name' => $route->getName()
                ];
            }
        }
        
        if (!empty($touristSitesNewRoutes)) {
            echo "   ✅ Routes للمواقع السياحية الجديدة:\n";
            foreach ($touristSitesNewRoutes as $route) {
                echo "      - {$route['methods']} {$route['uri']} ({$route['name']})\n";
            }
        } else {
            echo "   ❌ لا توجد routes للمواقع السياحية الجديدة\n";
        }
        
        // فحص admin.auth middleware
        $adminAuthRoutes = 0;
        foreach ($routes as $route) {
            if (in_array('admin.auth', $route->gatherMiddleware())) {
                $adminAuthRoutes++;
            }
        }
        echo "   Routes محمية بـ admin.auth: $adminAuthRoutes\n";
        
    } catch (Exception $e) {
        echo "   ❌ خطأ في فحص routes: " . $e->getMessage() . "\n";
    }
    echo "\n";
    
    // 5. فحص Views
    echo "5. Views:\n";
    $views = [
        'admin.tourist-sites-new.index' => 'قائمة المواقع السياحية الجديدة',
        'admin.tourist-sites-new.create' => 'إضافة موقع سياحي جديد',
        'admin.tourist-sites-new.show' => 'عرض الموقع السياحي',
        'admin.tourist-sites-new.edit' => 'تعديل الموقع السياحي',
        'admin.login' => 'صفحة تسجيل الدخول'
    ];
    
    foreach ($views as $view => $description) {
        $viewPath = str_replace('.', '/', $view) . '.blade.php';
        $fullPath = resource_path('views/' . $viewPath);
        
        if (file_exists($fullPath)) {
            echo "   ✅ $view ($description): موجود\n";
        } else {
            echo "   ❌ $view ($description): غير موجود في $fullPath\n";
        }
    }
    echo "\n";
    
    // 6. فحص Middleware
    echo "6. Middleware:\n";
    $middlewareAliases = $app['router']->getMiddleware();
    
    if (isset($middlewareAliases['admin.auth'])) {
        echo "   ✅ admin.auth middleware مسجل: {$middlewareAliases['admin.auth']}\n";
        
        // فحص أن الـ middleware class موجود
        $middlewareClass = $middlewareAliases['admin.auth'];
        if (class_exists($middlewareClass)) {
            echo "   ✅ $middlewareClass: موجود\n";
        } else {
            echo "   ❌ $middlewareClass: غير موجود\n";
        }
    } else {
        echo "   ❌ admin.auth middleware غير مسجل\n";
    }
    echo "\n";
    
    // 7. فحص Session
    echo "7. Session:\n";
    echo "   Session Driver: " . config('session.driver') . "\n";
    echo "   Session Lifetime: " . config('session.lifetime') . " دقيقة\n";
    echo "   Session Domain: " . (config('session.domain') ?: 'null') . "\n";
    echo "   Session Secure: " . (config('session.secure') ? 'true' : 'false') . "\n";
    echo "\n";
    
    // 8. فحص التخزين المؤقت
    echo "8. التخزين المؤقت:\n";
    $cacheDrivers = [
        'cache' => config('cache.default'),
        'session' => config('session.driver'),
        'view' => config('view.compiled') ? 'مفعل' : 'معطل'
    ];
    
    foreach ($cacheDrivers as $type => $driver) {
        echo "   $type: $driver\n";
    }
    echo "\n";
    
    // 9. فحص الملفات المهمة
    echo "9. الملفات المهمة:\n";
    $importantFiles = [
        '.env' => 'إعدادات البيئة',
        'storage/app/public' => 'تخزين الملفات',
        'public/storage' => 'رابط التخزين',
        'bootstrap/cache' => 'تخزين مؤقت Laravel'
    ];
    
    foreach ($importantFiles as $file => $description) {
        if (file_exists($file)) {
            if (is_dir($file)) {
                $writable = is_writable($file) ? 'قابل للكتابة' : 'غير قابل للكتابة';
                echo "   ✅ $file ($description): مجلد - $writable\n";
            } else {
                echo "   ✅ $file ($description): ملف\n";
            }
        } else {
            echo "   ❌ $file ($description): غير موجود\n";
        }
    }
    echo "\n";
    
    // 10. اختبار Route محدد
    echo "10. اختبار Route محدد:\n";
    try {
        $url = url('/dashboard/tourist-sites-new');
        echo "   URL: $url\n";
        
        // محاولة الحصول على route
        $route = Route::getRoutes()->match(Request::create('/dashboard/tourist-sites-new', 'GET'));
        if ($route) {
            echo "   ✅ Route موجود\n";
            echo "   Controller: " . (is_array($route->getAction('uses')) ? implode('@', $route->getAction('uses')) : $route->getAction('uses')) . "\n";
            echo "   Middleware: " . implode(', ', $route->gatherMiddleware()) . "\n";
        } else {
            echo "   ❌ Route غير موجود\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ خطأ في اختبار Route: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== انتهى التشخيص ===\n";
    
} catch (Exception $e) {
    echo "خطأ في تشغيل Laravel: " . $e->getMessage() . "\n";
    echo "تتبع الخطأ:\n" . $e->getTraceAsString() . "\n";
}
?>
