<?php
/**
 * ملف تشخيص مشاكل السيرفر
 * يمكن تشغيله مباشرة على السيرفر للتشخيص
 */

echo "<h1>تشخيص مشاكل السيرفر - البادية</h1>";

// 1. فحص إصدار PHP
echo "<h2>1. إصدار PHP</h2>";
echo "PHP Version: " . phpversion() . "<br>";

// 2. فحص امتدادات PHP المطلوبة
echo "<h2>2. امتدادات PHP المطلوبة</h2>";
$required_extensions = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath', 'fileinfo'];
foreach ($required_extensions as $ext) {
    echo "$ext: " . (extension_loaded($ext) ? "✅ متوفر" : "❌ غير متوفر") . "<br>";
}

// 3. فحص إعدادات PHP
echo "<h2>3. إعدادات PHP</h2>";
echo "max_execution_time: " . ini_get('max_execution_time') . "<br>";
echo "memory_limit: " . ini_get('memory_limit') . "<br>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";

// 4. فحص ملف .env
echo "<h2>4. فحص ملف .env</h2>";
if (file_exists('.env')) {
    echo "✅ ملف .env موجود<br>";
    $env_content = file_get_contents('.env');
    
    // فحص المتغيرات المهمة
    $important_vars = ['APP_ENV', 'APP_DEBUG', 'APP_URL', 'DB_CONNECTION', 'DB_HOST', 'DB_DATABASE'];
    foreach ($important_vars as $var) {
        if (strpos($env_content, $var) !== false) {
            echo "✅ $var موجود في .env<br>";
        } else {
            echo "❌ $var غير موجود في .env<br>";
        }
    }
} else {
    echo "❌ ملف .env غير موجود<br>";
}

// 5. فحص قاعدة البيانات
echo "<h2>5. فحص قاعدة البيانات</h2>";
try {
    if (file_exists('vendor/autoload.php')) {
        require_once 'vendor/autoload.php';
        
        // تحميل Laravel
        $app = require_once 'bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        
        // فحص الاتصال بقاعدة البيانات
        $pdo = DB::connection()->getPdo();
        echo "✅ الاتصال بقاعدة البيانات ناجح<br>";
        
        // فحص الجداول المطلوبة
        $tables = ['tourist_site_news', 'tourist_image_news', 'users'];
        foreach ($tables as $table) {
            try {
                $count = DB::table($table)->count();
                echo "✅ جدول $table موجود ويحتوي على $count سجل<br>";
            } catch (Exception $e) {
                echo "❌ جدول $table غير موجود أو به مشكلة<br>";
            }
        }
        
    } else {
        echo "❌ ملف vendor/autoload.php غير موجود<br>";
        echo "يرجى تشغيل: composer install<br>";
    }
} catch (Exception $e) {
    echo "❌ خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage() . "<br>";
}

// 6. فحص ملفات Laravel الأساسية
echo "<h2>6. فحص ملفات Laravel الأساسية</h2>";
$required_files = [
    'artisan',
    'bootstrap/app.php',
    'config/app.php',
    'routes/web.php',
    'app/Http/Controllers/TouristSiteNewController.php',
    'resources/views/admin/tourist-sites-new/index.blade.php'
];

foreach ($required_files as $file) {
    if (file_exists($file)) {
        echo "✅ $file موجود<br>";
    } else {
        echo "❌ $file غير موجود<br>";
    }
}

// 7. فحص أذونات الملفات
echo "<h2>7. فحص أذونات الملفات</h2>";
$directories = ['storage', 'bootstrap/cache', 'public'];
foreach ($directories as $dir) {
    if (is_dir($dir)) {
        $perms = substr(sprintf('%o', fileperms($dir)), -4);
        echo "$dir: $perms ";
        if (is_writable($dir)) {
            echo "✅ قابل للكتابة<br>";
        } else {
            echo "❌ غير قابل للكتابة<br>";
        }
    } else {
        echo "❌ $dir غير موجود<br>";
    }
}

// 8. فحص routes
echo "<h2>8. فحص Routes</h2>";
try {
    if (isset($app)) {
        $routes = Route::getRoutes();
        $touristSitesNewRoutes = [];
        
        foreach ($routes as $route) {
            if (strpos($route->uri(), 'tourist-sites-new') !== false) {
                $touristSitesNewRoutes[] = $route->uri() . ' [' . implode('|', $route->methods()) . ']';
            }
        }
        
        if (!empty($touristSitesNewRoutes)) {
            echo "✅ Routes للمواقع السياحية الجديدة:<br>";
            foreach ($touristSitesNewRoutes as $route) {
                echo "&nbsp;&nbsp;- $route<br>";
            }
        } else {
            echo "❌ لا توجد routes للمواقع السياحية الجديدة<br>";
        }
    }
} catch (Exception $e) {
    echo "❌ خطأ في فحص routes: " . $e->getMessage() . "<br>";
}

// 9. فحص middleware
echo "<h2>9. فحص Middleware</h2>";
try {
    if (isset($app)) {
        $middleware = $app['router']->getMiddleware();
        if (isset($middleware['admin.auth'])) {
            echo "✅ admin.auth middleware مسجل<br>";
        } else {
            echo "❌ admin.auth middleware غير مسجل<br>";
        }
    }
} catch (Exception $e) {
    echo "❌ خطأ في فحص middleware: " . $e->getMessage() . "<br>";
}

// 10. فحص session
echo "<h2>10. فحص Session</h2>";
try {
    if (session_start()) {
        echo "✅ Session يعمل<br>";
        echo "Session ID: " . session_id() . "<br>";
        
        // فحص متغيرات session المهمة
        $important_session_vars = ['admin_access', 'admin_secret'];
        foreach ($important_session_vars as $var) {
            if (isset($_SESSION[$var])) {
                echo "✅ $_SESSION[$var] موجود<br>";
            } else {
                echo "❌ $_SESSION[$var] غير موجود<br>";
            }
        }
    } else {
        echo "❌ Session لا يعمل<br>";
    }
} catch (Exception $e) {
    echo "❌ خطأ في فحص session: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<p><strong>ملاحظة:</strong> إذا وجدت أي أخطاء، يرجى إصلاحها قبل المتابعة.</p>";
?>
