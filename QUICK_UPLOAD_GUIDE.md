# دليل الرفع السريع للاستضافة العادية - البادية

## 🚀 خطوات سريعة للنشر

### 1️⃣ إعداد الاستضافة (5 دقائق)

#### **في cPanel:**
1. **أنشئ قاعدة بيانات:**
   - MySQL Databases → Create Database
   - اسم قاعدة البيانات: `your_domain_db`
   - أنشئ مستخدم جديد وأضفه للقاعدة

2. **افتح File Manager:**
   - اذهب إلى `public_html`
   - احذف ملف `index.html` إذا كان موجوداً

### 2️⃣ رفع الملفات (10 دقائق)

#### **طريقة 1: File Manager (cPanel)**
1. ضغط جميع ملفات المشروع في ملف ZIP
2. رفع الملف المضغوط إلى `public_html`
3. استخراج الملفات
4. حذف ملف ZIP

#### **طريقة 2: FTP**
```bash
# استخدام FileZilla أو أي عميل FTP
# رفع جميع الملفات إلى public_html
```

### 3️⃣ إعداد المشروع (5 دقائق)

#### **في Terminal (cPanel):**
```bash
# الانتقال لمجلد المشروع
cd public_html

# نسخ ملف الإعدادات
cp shared_hosting.env .env

# تحديث التبعيات
composer install --optimize-autoloader --no-dev

# إنشاء مفتاح التطبيق
php artisan key:generate

# تشغيل migrations
php artisan migrate --force

# إنشاء storage link
php artisan storage:link

# تنظيف وإعادة بناء التخزين المؤقت
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4️⃣ تحديث إعدادات .env

```env
# غيّر هذه القيم حسب استضافتك:
APP_URL=https://your-domain.com
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
ADMIN_SECRET=your-secure-password-here
MAIL_FROM_ADDRESS=noreply@your-domain.com
```

### 5️⃣ إعداد أذونات الملفات

```bash
# إعداد أذونات صحيحة
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env
chmod 644 .htaccess
```

## ✅ اختبار الموقع

### **صفحات للاختبار:**
1. **الصفحة الرئيسية:** `https://your-domain.com`
2. **لوحة التحكم:** `https://your-domain.com/dashboard`
3. **تسجيل الدخول:** `https://your-domain.com/admin/login`

### **اختبار الوظائف:**
1. تسجيل الدخول بلوحة التحكم
2. إضافة محافظة جديدة
3. إضافة ولاية جديدة
4. إضافة موقع سياحي جديد
5. رفع صورة

## 🔧 حل المشاكل السريع

### ❌ **خطأ 500 Internal Server Error**
```bash
# فحص سجلات الأخطاء
tail -f storage/logs/laravel.log

# إعادة إنشاء التخزين المؤقت
php artisan config:clear
php artisan config:cache
```

### ❌ **مشكلة في قاعدة البيانات**
```bash
# فحص الاتصال
php artisan tinker --execute="DB::connection()->getPdo();"

# تشغيل migrations
php artisan migrate --force
```

### ❌ **الصور لا تظهر**
```bash
# إنشاء storage link
php artisan storage:link

# فحص أذونات مجلد storage
chmod -R 755 storage/
```

### ❌ **صفحة لا تفتح**
```bash
# تنظيف routes
php artisan route:clear
php artisan route:cache
```

## 📝 قائمة التحقق السريعة

### **قبل النشر:**
- [ ] قاعدة البيانات جاهزة
- [ ] ملف .env محدث
- [ ] جميع الملفات مرفوعة
- [ ] composer install تم تشغيله

### **بعد النشر:**
- [ ] الموقع يفتح
- [ ] لوحة التحكم تعمل
- [ ] تسجيل الدخول يعمل
- [ ] قاعدة البيانات تعمل
- [ ] رفع الصور يعمل

## 🎯 نصائح مهمة

1. **احتفظ بنسخة احتياطية** من قاعدة البيانات
2. **استخدم كلمات مرور قوية** لـ ADMIN_SECRET
3. **راقب سجلات الأخطاء** بانتظام
4. **اختبر جميع الوظائف** بعد النشر
5. **استخدم HTTPS** دائماً

## 📞 في حالة المشاكل

### **فحص سريع:**
```bash
# فحص حالة Laravel
php artisan --version

# فحص قاعدة البيانات
php artisan tinker --execute="DB::table('users')->count();"

# فحص storage
ls -la storage/

# فحص logs
tail -f storage/logs/laravel.log
```

### **إعادة تعيين سريعة:**
```bash
# تنظيف كامل
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# إعادة بناء
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

**🎉 تهانينا! موقع البادية جاهز للعمل!**

**الوقت المطلوب: 20 دقيقة فقط! ⏰**
