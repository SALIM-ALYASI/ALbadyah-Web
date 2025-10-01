<?php
/**
 * اختبار سريع للـ route
 * يمكن تشغيله عبر: php test_route.php
 */

require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

try {
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "=== اختبار Route للمواقع السياحية الجديدة ===\n\n";
    
    // اختبار URL
    $url = '/dashboard/tourist-sites-new';
    echo "URL: $url\n";
    
    // محاولة الحصول على route
    try {
        $route = Route::getRoutes()->match(Request::create($url, 'GET'));
        
        if ($route) {
            echo "✅ Route موجود!\n";
            echo "Controller: " . (is_array($route->getAction('uses')) ? implode('@', $route->getAction('uses')) : $route->getAction('uses')) . "\n";
            echo "Middleware: " . implode(', ', $route->gatherMiddleware()) . "\n";
            echo "Name: " . $route->getName() . "\n";
            
            // فحص middleware admin.auth
            if (in_array('admin.auth', $route->gatherMiddleware())) {
                echo "✅ محمي بـ admin.auth middleware\n";
                
                // فحص إعدادات middleware
                $middlewareAliases = $app['router']->getMiddleware();
                if (isset($middlewareAliases['admin.auth'])) {
                    echo "✅ admin.auth middleware مسجل: {$middlewareAliases['admin.auth']}\n";
                    
                    // فحص أن الـ middleware class موجود
                    $middlewareClass = $middlewareAliases['admin.auth'];
                    if (class_exists($middlewareClass)) {
                        echo "✅ $middlewareClass موجود\n";
                    } else {
                        echo "❌ $middlewareClass غير موجود\n";
                    }
                } else {
                    echo "❌ admin.auth middleware غير مسجل\n";
                }
            } else {
                echo "⚠️ غير محمي بـ admin.auth middleware\n";
            }
            
        } else {
            echo "❌ Route غير موجود!\n";
        }
        
    } catch (Exception $e) {
        echo "❌ خطأ في الحصول على Route: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== اختبار Controller ===\n";
    
    // فحص Controller
    $controllerClass = 'App\Http\Controllers\TouristSiteNewController';
    if (class_exists($controllerClass)) {
        echo "✅ $controllerClass موجود\n";
        
        // فحص دالة index
        if (method_exists($controllerClass, 'index')) {
            echo "✅ دالة index موجودة\n";
        } else {
            echo "❌ دالة index غير موجودة\n";
        }
        
        // فحص دالة create
        if (method_exists($controllerClass, 'create')) {
            echo "✅ دالة create موجودة\n";
        } else {
            echo "❌ دالة create غير موجودة\n";
        }
        
    } else {
        echo "❌ $controllerClass غير موجود\n";
    }
    
    echo "\n=== اختبار Views ===\n";
    
    // فحص Views
    $views = [
        'admin.tourist-sites-new.index' => 'قائمة المواقع السياحية الجديدة',
        'admin.tourist-sites-new.create' => 'إضافة موقع سياحي جديد',
        'admin.tourist-sites-new.show' => 'عرض الموقع السياحي',
        'admin.tourist-sites-new.edit' => 'تعديل الموقع السياحي'
    ];
    
    foreach ($views as $view => $description) {
        $viewPath = str_replace('.', '/', $view) . '.blade.php';
        $fullPath = resource_path('views/' . $viewPath);
        
        if (file_exists($fullPath)) {
            echo "✅ $view ($description): موجود\n";
        } else {
            echo "❌ $view ($description): غير موجود في $fullPath\n";
        }
    }
    
    echo "\n=== اختبار Model ===\n";
    
    // فحص Model
    $modelClass = 'App\Models\TouristSiteNew';
    if (class_exists($modelClass)) {
        echo "✅ $modelClass موجود\n";
        
        try {
            $count = $modelClass::count();
            echo "✅ عدد السجلات: $count\n";
        } catch (Exception $e) {
            echo "❌ خطأ في فحص السجلات: " . $e->getMessage() . "\n";
        }
        
    } else {
        echo "❌ $modelClass غير موجود\n";
    }
    
    echo "\n=== اختبار قاعدة البيانات ===\n";
    
    try {
        DB::connection()->getPdo();
        echo "✅ الاتصال بقاعدة البيانات ناجح\n";
        
        // فحص الجداول
        $tables = ['tourist_site_news', 'tourist_image_news'];
        foreach ($tables as $table) {
            try {
                $count = DB::table($table)->count();
                echo "✅ جدول $table: $count سجل\n";
            } catch (Exception $e) {
                echo "❌ جدول $table: " . $e->getMessage() . "\n";
            }
        }
        
    } catch (Exception $e) {
        echo "❌ خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== اختبار Session ===\n";
    
    // فحص إعدادات Session
    echo "Session Driver: " . config('session.driver') . "\n";
    echo "Session Lifetime: " . config('session.lifetime') . " دقيقة\n";
    echo "Session Domain: " . (config('session.domain') ?: 'null') . "\n";
    echo "Session Secure: " . (config('session.secure') ? 'true' : 'false') . "\n";
    
    // فحص ADMIN_SECRET
    $adminSecret = env('ADMIN_SECRET');
    if ($adminSecret) {
        echo "✅ ADMIN_SECRET محدد\n";
    } else {
        echo "❌ ADMIN_SECRET غير محدد\n";
    }
    
    echo "\n=== انتهى الاختبار ===\n";
    
} catch (Exception $e) {
    echo "خطأ في تشغيل Laravel: " . $e->getMessage() . "\n";
    echo "تتبع الخطأ:\n" . $e->getTraceAsString() . "\n";
}
?>
