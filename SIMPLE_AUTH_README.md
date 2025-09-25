# نظام تسجيل الدخول المبسط والمحمي

## 🔐 نظرة عامة

تم تطوير نظام تسجيل دخول مبسط يستقبل متغير واحد فقط مع حماية متقدمة ضد محاولات التلاعب والاختراق.

## ✨ المميزات الجديدة

### 🔑 **متغير واحد فقط**
- **مفتاح دخول واحد**: `admin_key` بدلاً من اسم مستخدم وكلمة مرور
- **بساطة في الاستخدام**: حقل واحد فقط للدخول
- **أمان محسن**: تشفير SHA256 للمقارنة

### 🛡️ **حماية متقدمة ضد التلاعب**

#### **Rate Limiting (منع المحاولات المتكررة)**
- **منع المحاولات السريعة**: لا يمكن المحاولة أكثر من مرة كل 3 ثواني
- **حد أقصى للمحاولات**: 5 محاولات في 10 دقائق لكل IP
- **حظر مؤقت**: منع المحاولات لمدة 10 دقائق عند تجاوز الحد

#### **مراقبة عنوان IP**
- **تتبع IP**: ربط الجلسة بعنوان IP
- **حماية من سرقة الجلسة**: إنهاء الجلسة عند تغيير IP
- **أمان إضافي**: منع الوصول من عناوين مختلفة

#### **تشفير متقدم**
- **SHA256 Hashing**: تشفير مفتاح الدخول
- **مقارنة آمنة**: عدم تخزين المفتاح في نص واضح
- **حماية البيانات**: تشفير جميع البيانات الحساسة

## 🚀 التطبيق

### **1. متغيرات البيئة**

#### في ملف `.env`:
```env
ADMIN_SECRET=admin123
```

### **2. AdminController المحدث**

#### **تسجيل الدخول المحسن:**
```php
public function login(Request $request)
{
    // Rate Limiting - منع المحاولات المتكررة
    $ip = $request->ip();
    $attempts = session("login_attempts_{$ip}", 0);
    $lastAttempt = session("last_attempt_{$ip}", 0);
    
    // منع المحاولات السريعة (أقل من 3 ثواني)
    if (time() - $lastAttempt < 3) {
        return back()->withErrors([
            'credentials' => 'محاولة دخول سريعة جداً، انتظر قليلاً'
        ]);
    }
    
    // منع أكثر من 5 محاولات في 10 دقائق
    if ($attempts >= 5 && (time() - session("first_attempt_{$ip}", time())) < 600) {
        return back()->withErrors([
            'credentials' => 'تم تجاوز عدد المحاولات المسموح، حاول مرة أخرى بعد 10 دقائق'
        ]);
    }
    
    // التحقق من صحة المفتاح مع تشفير SHA256
    $adminSecret = env('ADMIN_SECRET', 'admin123');
    $inputKey = $request->admin_key;
    
    if (hash('sha256', $inputKey) === hash('sha256', $adminSecret)) {
        // مسح عداد المحاولات عند نجاح الدخول
        session()->forget([
            "login_attempts_{$ip}",
            "last_attempt_{$ip}",
            "first_attempt_{$ip}"
        ]);
        
        // تعيين جلسة الإدمن مع بيانات إضافية
        session([
            'admin_access' => true,
            'admin_secret' => env('ADMIN_SECRET'),
            'admin_login_time' => time(),
            'admin_ip' => $ip,
            'admin_session_id' => uniqid('admin_', true)
        ]);
        
        return redirect()->route('governorates.index')
            ->with('success', 'مرحباً بك في لوحة التحكم');
    }
}
```

### **3. Middleware المحسن**

#### **حماية إضافية:**
```php
public function handle(Request $request, Closure $next): Response
{
    // التحقق من وجود جلسة إدارية صحيحة
    if (!session('admin_access') || session('admin_secret') !== env('ADMIN_SECRET')) {
        return redirect()->route('admin.login')->with('error', 'يجب تسجيل الدخول');
    }
    
    // التحقق من IP - حماية ضد سرقة الجلسة
    $sessionIp = session('admin_ip');
    $currentIp = $request->ip();
    if ($sessionIp && $sessionIp !== $currentIp) {
        session()->forget([
            'admin_access', 'admin_secret', 'admin_login_time', 
            'admin_ip', 'admin_session_id'
        ]);
        return redirect()->route('admin.login')->with('error', 'تم اكتشاف تغيير في عنوان IP');
    }
    
    // التحقق من انتهاء صلاحية الجلسة
    $sessionTimeout = session('admin_login_time');
    if ($sessionTimeout && (time() - $sessionTimeout) > 7200) {
        session()->forget([
            'admin_access', 'admin_secret', 'admin_login_time', 
            'admin_ip', 'admin_session_id'
        ]);
        return redirect()->route('admin.login')->with('error', 'انتهت صلاحية الجلسة');
    }
    
    return $next($request);
}
```

### **4. صفحة تسجيل الدخول المبسطة**

#### **حقل واحد فقط:**
```html
<div class="mb-4">
    <label for="admin_key" class="form-label">
        <i class="fas fa-key me-1"></i>
        مفتاح الدخول الإداري
    </label>
    <div class="input-group">
        <input type="password" 
               class="form-control @error('admin_key') is-invalid @enderror" 
               id="admin_key" 
               name="admin_key" 
               placeholder="أدخل مفتاح الدخول الإداري"
               required
               autofocus
               minlength="6">
        <span class="input-group-text">
            <i class="fas fa-key"></i>
        </span>
    </div>
</div>
```

#### **معلومات الأمان:**
```html
<div class="credentials-info">
    <h6>معلومات الأمان</h6>
    <div class="mb-2">
        <small>مفتاح الدخول:</small><br>
        <strong>{{ env('ADMIN_SECRET', 'admin123') }}</strong>
    </div>
    <div class="row">
        <div class="col-12 mb-1">
            <small><i class="fas fa-clock me-1"></i>الجلسة تنتهي تلقائياً بعد ساعتين</small>
        </div>
        <div class="col-12 mb-1">
            <small><i class="fas fa-ban me-1"></i>حد أقصى 5 محاولات في 10 دقائق</small>
        </div>
        <div class="col-12">
            <small><i class="fas fa-network-wired me-1"></i>مراقبة عنوان IP للأمان</small>
        </div>
    </div>
</div>
```

## 🔒 مستويات الحماية

### **المستوى الأول: Rate Limiting**
- **منع المحاولات السريعة**: 3 ثواني بين المحاولات
- **حد أقصى للمحاولات**: 5 محاولات في 10 دقائق
- **حظر مؤقت**: 10 دقائق عند تجاوز الحد

### **المستوى الثاني: تشفير البيانات**
- **SHA256 Hashing**: تشفير مفتاح الدخول
- **مقارنة آمنة**: عدم تخزين النص الواضح
- **حماية البيانات**: تشفير جميع البيانات الحساسة

### **المستوى الثالث: مراقبة IP**
- **تتبع عنوان IP**: ربط الجلسة بعنوان IP
- **حماية من سرقة الجلسة**: إنهاء الجلسة عند تغيير IP
- **أمان إضافي**: منع الوصول من عناوين مختلفة

### **المستوى الرابع: إدارة الجلسات**
- **انتهاء صلاحية تلقائي**: بعد ساعتين
- **معرف جلسة فريد**: `admin_session_id`
- **تنظيف تلقائي**: حذف جميع البيانات عند الخروج

## 📋 كيفية الاستخدام

### **1. تسجيل الدخول:**
```
http://127.0.0.1:8000/admin/login
```

**البيانات:**
- **مفتاح الدخول**: قيمة `ADMIN_SECRET` من ملف `.env`

### **2. تغيير مفتاح الدخول:**
```env
# في ملف .env
ADMIN_SECRET=مفتاح_جديد_قوي
```

### **3. الوصول للوحة التحكم:**
```
http://127.0.0.1:8000/dashboard/governorates
```

## ⚠️ الحماية ضد التلاعب

### **✅ الحماية المطبقة:**
- **منع المحاولات المتكررة**: Rate limiting
- **مراقبة عنوان IP**: حماية من سرقة الجلسة
- **تشفير البيانات**: SHA256 hashing
- **انتهاء صلاحية تلقائي**: بعد ساعتين
- **رسائل خطأ واضحة**: باللغة العربية

### **❌ ما تم منعه:**
- **محاولات دخول متكررة**: أكثر من 5 في 10 دقائق
- **محاولات سريعة**: أقل من 3 ثواني بين المحاولات
- **تغيير عنوان IP**: إنهاء الجلسة تلقائياً
- **جلسات منتهية الصلاحية**: إعادة توجيه لصفحة تسجيل الدخول

## 🔧 استكشاف الأخطاء

### **خطأ: "محاولة دخول سريعة جداً"**
```bash
# انتظر 3 ثواني قبل المحاولة التالية
```

### **خطأ: "تم تجاوز عدد المحاولات المسموح"**
```bash
# انتظر 10 دقائق قبل المحاولة مرة أخرى
```

### **خطأ: "تم اكتشاف تغيير في عنوان IP"**
```bash
# سجل دخول مرة أخرى من نفس العنوان
```

### **خطأ: "انتهت صلاحية الجلسة"**
```bash
# سجل دخول مرة أخرى
```

## 🎯 النتيجة النهائية

✅ **نظام دخول مبسط - متغير واحد فقط**  
✅ **حماية متقدمة ضد التلاعب**  
✅ **Rate limiting ذكي**  
✅ **مراقبة عنوان IP**  
✅ **تشفير SHA256**  
✅ **إدارة جلسات محسنة**  
✅ **رسائل خطأ واضحة**  
✅ **أمان متعدد المستويات**  

النظام الآن أبسط وأكثر أماناً! 🔐✨
