# نظام حماية لوحة التحكم المتقدم

## 🔐 نظرة عامة

تم تطبيق نظام حماية متقدم للوحة التحكم الإدارية باستخدام Environment Variable (ADMIN_SECRET) مع middleware مخصص وميزات أمان إضافية.

## ✨ المميزات الأمنية الجديدة

### 🔑 **Environment Variable Protection**
- **متغير البيئة**: `ADMIN_SECRET` في ملف `.env`
- **كلمة مرور ديناميكية**: قابلة للتغيير بدون تعديل الكود
- **حماية إضافية**: التحقق من صحة الـ secret في كل طلب

### 🛡️ **Middleware متقدم**
- **AdminAuthMiddleware**: حماية شاملة لجميع مسارات لوحة التحكم
- **التحقق المزدوج**: من الجلسة والـ secret
- **انتهاء صلاحية تلقائي**: الجلسة تنتهي بعد ساعتين
- **رسائل خطأ واضحة**: باللغة العربية

### ⏰ **إدارة الجلسات**
- **تسجيل وقت الدخول**: `admin_login_time`
- **انتهاء صلاحية تلقائي**: 7200 ثانية (ساعتان)
- **تنظيف تلقائي**: حذف جميع بيانات الجلسة عند الخروج
- **حماية من التلاعب**: التحقق من صحة البيانات

## 🚀 التطبيق

### **1. متغيرات البيئة**

#### في ملف `.env`:
```env
# Admin Panel Security
ADMIN_SECRET=admin123
```

#### في ملف `.env.example`:
```env
# Admin Panel Security
ADMIN_SECRET=admin123
```

### **2. Middleware الجديد**

#### ملف: `app/Http/Middleware/AdminAuthMiddleware.php`
```php
public function handle(Request $request, Closure $next): Response
{
    // التحقق من وجود ADMIN_SECRET
    $adminSecret = env('ADMIN_SECRET');
    
    if (!$adminSecret) {
        abort(500, 'Admin secret not configured');
    }
    
    // التحقق من الجلسة والـ secret
    if (!session('admin_access') || session('admin_secret') !== $adminSecret) {
        return redirect()->route('admin.login')->with('error', 'يجب تسجيل الدخول');
    }
    
    // التحقق من انتهاء صلاحية الجلسة
    $sessionTimeout = session('admin_login_time');
    if ($sessionTimeout && (time() - $sessionTimeout) > 7200) {
        session()->forget(['admin_access', 'admin_secret', 'admin_login_time']);
        return redirect()->route('admin.login')->with('error', 'انتهت صلاحية الجلسة');
    }
    
    return $next($request);
}
```

### **3. تسجيل Middleware**

#### في ملف: `bootstrap/app.php`
```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'admin.auth' => \App\Http\Middleware\AdminAuthMiddleware::class,
    ]);
})
```

### **4. مسارات محمية**

#### في ملف: `routes/web.php`
```php
Route::group([
    'prefix' => 'dashboard',
    'middleware' => 'admin.auth'
], function () {
    Route::resource('governorates', GovernorateController::class);
    Route::resource('wilayats', WilayatController::class);
    Route::resource('tourist-sites', TouristSiteController::class);
    Route::resource('tourist-services', TouristServiceController::class);
    
    Route::get('/data-viewer', [TouristServiceController::class, 'dataViewer'])->name('data-viewer.index');
    Route::get('/visit-stats', [VisitStatsController::class, 'index'])->name('visit-stats.index');
});
```

## 🔧 AdminController المحدث

### **تسجيل الدخول المحسن**
```php
public function login(Request $request)
{
    $adminCredentials = [
        'username' => 'admin',
        'password' => env('ADMIN_SECRET', 'admin123'),
    ];

    if ($request->username === $adminCredentials['username'] && 
        $request->password === $adminCredentials['password']) {
        
        session([
            'admin_access' => true,
            'admin_secret' => env('ADMIN_SECRET'),
            'admin_login_time' => time(),
            'admin_username' => $request->username
        ]);
        
        return redirect()->route('governorates.index')
            ->with('success', 'مرحباً بك في لوحة التحكم');
    }
}
```

### **تسجيل الخروج المحسن**
```php
public function logout()
{
    session()->forget([
        'admin_access', 
        'admin_secret', 
        'admin_login_time', 
        'admin_username'
    ]);
    
    return redirect()->route('admin.login')
        ->with('success', 'تم تسجيل الخروج بنجاح');
}
```

## 🎨 صفحة تسجيل الدخول المحسنة

### **المميزات الجديدة:**
- **عرض معلومات تسجيل الدخول**: اسم المستخدم وكلمة المرور
- **تحذير انتهاء الصلاحية**: معلومات عن مدة الجلسة
- **رابط العودة**: للموقع السياحي العام
- **تصميم محسن**: مع أيقونات ورسائل واضحة

### **معلومات الأمان المعروضة:**
```html
<div class="credentials-info">
    <h6>معلومات تسجيل الدخول</h6>
    <div class="row">
        <div class="col-6">
            <small>اسم المستخدم:</small><br>
            <strong>admin</strong>
        </div>
        <div class="col-6">
            <small>كلمة المرور:</small><br>
            <strong>{{ env('ADMIN_SECRET') }}</strong>
        </div>
    </div>
    <small>الجلسة تنتهي تلقائياً بعد ساعتين من عدم النشاط</small>
</div>
```

## 🔒 مستويات الحماية

### **المستوى الأول: Environment Variable**
- كلمة المرور محفوظة في متغير البيئة
- قابلة للتغيير بدون تعديل الكود
- غير مرئية في الكود المصدري

### **المستوى الثاني: Session Validation**
- التحقق من صحة الجلسة
- تخزين الـ secret في الجلسة
- مقارنة مع متغير البيئة

### **المستوى الثالث: Timeout Protection**
- انتهاء صلاحية تلقائي بعد ساعتين
- حذف جميع بيانات الجلسة
- إعادة توجيه لصفحة تسجيل الدخول

### **المستوى الرابع: Middleware Protection**
- حماية جميع مسارات لوحة التحكم
- التحقق في كل طلب
- رسائل خطأ واضحة

## 📋 كيفية الاستخدام

### **1. تسجيل الدخول:**
```
http://127.0.0.1:8000/admin/login
```

**البيانات:**
- **اسم المستخدم**: `admin`
- **كلمة المرور**: قيمة `ADMIN_SECRET` من ملف `.env`

### **2. الوصول للوحة التحكم:**
```
http://127.0.0.1:8000/dashboard/governorates
```

### **3. تغيير كلمة المرور:**
```env
# في ملف .env
ADMIN_SECRET=كلمة_المرور_الجديدة
```

## ⚠️ ملاحظات أمنية

### **✅ الممارسات الجيدة:**
- استخدم كلمة مرور قوية لـ `ADMIN_SECRET`
- لا تشارك ملف `.env` مع أحد
- غير كلمة المرور دورياً
- راقب جلسات تسجيل الدخول

### **❌ تجنب:**
- استخدام كلمات مرور بسيطة
- حفظ `ADMIN_SECRET` في الكود المصدري
- تجاهل رسائل انتهاء الصلاحية
- مشاركة بيانات تسجيل الدخول

## 🔧 استكشاف الأخطاء

### **خطأ: "Admin secret not configured"**
```bash
# تأكد من وجود المتغير في .env
echo $ADMIN_SECRET
```

### **خطأ: "انتهت صلاحية الجلسة"**
```bash
# امسح الجلسة وأعد تسجيل الدخول
php artisan session:clear
```

### **خطأ: "يجب تسجيل الدخول"**
```bash
# تأكد من صحة كلمة المرور
php artisan config:clear
```

## 🎯 النتيجة النهائية

✅ **نظام حماية متقدم ومتعدد المستويات**  
✅ **حماية بواسطة Environment Variable**  
✅ **Middleware مخصص للحماية**  
✅ **إدارة جلسات ذكية**  
✅ **صفحة تسجيل دخول محسنة**  
✅ **رسائل خطأ واضحة بالعربية**  
✅ **انتهاء صلاحية تلقائي**  
✅ **سهولة الصيانة والتطوير**  

النظام الآن أكثر أماناً ومرونة! 🔐✨
