# دليل النشر على الاستضافة العادية - البادية

## 📋 متطلبات الاستضافة

### ✅ المتطلبات الأساسية
- **PHP 8.1+** (يفضل PHP 8.2)
- **MySQL 5.7+** أو **MariaDB 10.3+**
- **Apache** أو **Nginx** مع mod_rewrite
- **SSL Certificate** (HTTPS)
- **مساحة تخزين:** 500MB على الأقل
- **ذاكرة:** 256MB على الأقل

### ✅ امتدادات PHP المطلوبة
```
pdo
pdo_mysql
mbstring
openssl
tokenizer
xml
ctype
json
bcmath
fileinfo
curl
gd
zip
```

## 🚀 خطوات النشر

### 1️⃣ إعداد الاستضافة

#### **إنشاء قاعدة البيانات:**
1. ادخل إلى cPanel
2. اختر "MySQL Databases"
3. أنشئ قاعدة بيانات جديدة
4. أنشئ مستخدم جديد
5. أضف المستخدم لقاعدة البيانات مع جميع الصلاحيات

#### **رفع الملفات:**
1. استخدم File Manager في cPanel
2. أو استخدم FTP/SFTP
3. ارفع جميع ملفات المشروع إلى `public_html`

### 2️⃣ إعداد ملف .env

```env
APP_NAME="البادية - Al-Badyah"
APP_ENV=production
APP_KEY=base64:your-generated-key-here
APP_DEBUG=false
APP_URL=https://your-domain.com

APP_LOCALE=ar
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=ar_SA

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

CACHE_STORE=database
FILESYSTEM_DISK=public

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@your-domain.com"
MAIL_FROM_NAME="${APP_NAME}"

ADMIN_SECRET=your-secure-admin-secret
```

### 3️⃣ تشغيل أوامر Laravel

#### **عبر Terminal في cPanel:**
```bash
# الانتقال لمجلد المشروع
cd public_html

# تحديث التبعيات
composer install --optimize-autoloader --no-dev

# إنشاء مفتاح التطبيق
php artisan key:generate

# تشغيل migrations
php artisan migrate --force

# إنشاء storage link
php artisan storage:link

# تنظيف التخزين المؤقت
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# إنشاء التخزين المؤقت للإنتاج
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4️⃣ إعداد أذونات الملفات

```bash
# إعداد أذونات الملفات
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env
chmod 644 .htaccess
```

## 🔧 إعدادات Apache

### ملف .htaccess الرئيسي

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]
    
    # Handle Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header always append X-Frame-Options SAMEORIGIN
    Header always set X-Content-Type-Options nosniff
    Header always set X-XSS-Protection "1; mode=block"
</IfModule>

# Cache Control
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>

# Gzip Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/css text/javascript application/javascript
</IfModule>
```

## 🗄️ إعداد قاعدة البيانات

### 1️⃣ إنشاء قاعدة البيانات
```sql
CREATE DATABASE al_badyah CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2️⃣ إنشاء مستخدم
```sql
CREATE USER 'al_badyah_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON al_badyah.* TO 'al_badyah_user'@'localhost';
FLUSH PRIVILEGES;
```

### 3️⃣ تشغيل Migrations
```bash
php artisan migrate --force
```

## 📧 إعداد البريد الإلكتروني

### إعدادات SMTP
```env
MAIL_MAILER=smtp
MAIL_HOST=mail.your-domain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@your-domain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@your-domain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## 🔐 إعدادات الأمان

### 1️⃣ حماية ملفات حساسة
```apache
<Files ".env">
    Order allow,deny
    Deny from all
</Files>

<Files "composer.json">
    Order allow,deny
    Deny from all
</Files>

<Files "artisan">
    Order allow,deny
    Deny from all
</Files>
```

### 2️⃣ حماية المجلدات
```apache
<DirectoryMatch "\.git">
    Order allow,deny
    Deny from all
</DirectoryMatch>

<DirectoryMatch "storage">
    Order allow,deny
    Deny from all
</DirectoryMatch>
```

## 📊 مراقبة الأداء

### 1️⃣ فحص سجلات الأخطاء
```bash
# سجلات Laravel
tail -f storage/logs/laravel.log

# سجلات Apache
tail -f /var/log/apache2/error.log
```

### 2️⃣ فحص استخدام الموارد
- مراقبة استخدام الذاكرة
- مراقبة استخدام مساحة التخزين
- مراقبة عدد العمليات

## 🔧 استكشاف الأخطاء

### مشاكل شائعة وحلولها:

#### ❌ **خطأ 500 Internal Server Error**
```bash
# فحص سجلات الأخطاء
tail -f storage/logs/laravel.log

# فحص أذونات الملفات
ls -la storage/
ls -la bootstrap/cache/
```

#### ❌ **مشكلة في قاعدة البيانات**
```bash
# فحص الاتصال
php artisan tinker --execute="DB::connection()->getPdo();"

# تشغيل migrations
php artisan migrate --force
```

#### ❌ **مشكلة في الصور**
```bash
# إنشاء storage link
php artisan storage:link

# فحص أذونات مجلد storage
chmod -R 755 storage/
```

#### ❌ **مشكلة في Routes**
```bash
# تنظيف routes
php artisan route:clear
php artisan route:cache

# فحص routes
php artisan route:list
```

## 📱 اختبار الموقع

### 1️⃣ اختبار الصفحات الأساسية
- الصفحة الرئيسية: `https://your-domain.com`
- لوحة التحكم: `https://your-domain.com/dashboard`
- تسجيل الدخول: `https://your-domain.com/admin/login`

### 2️⃣ اختبار الوظائف
- إضافة محافظة جديدة
- إضافة ولاية جديدة
- إضافة موقع سياحي جديد
- رفع الصور

## 🚀 تحسين الأداء

### 1️⃣ تفعيل التخزين المؤقت
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2️⃣ ضغط الملفات
```apache
# في .htaccess
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/css text/javascript
</IfModule>
```

### 3️⃣ تحسين الصور
- ضغط الصور قبل الرفع
- استخدام تنسيقات حديثة (WebP)
- تحديد أحجام مناسبة

## 📞 الدعم الفني

### في حالة وجود مشاكل:
1. فحص سجلات الأخطاء
2. التحقق من إعدادات .env
3. التحقق من أذونات الملفات
4. التحقق من إعدادات قاعدة البيانات
5. التواصل مع مزود الاستضافة

### معلومات مفيدة:
- **سجلات Laravel:** `storage/logs/laravel.log`
- **سجلات Apache:** `/var/log/apache2/error.log`
- **معلومات PHP:** `phpinfo()`
- **إعدادات PHP:** `php -i`

## 🎯 نصائح مهمة

1. **احتفظ بنسخة احتياطية** من قاعدة البيانات
2. **راقب استخدام الموارد** بانتظام
3. **حدث المشروع** بانتظام
4. **راقب سجلات الأخطاء** يومياً
5. **استخدم HTTPS** دائماً
6. **اختبار الموقع** بعد كل تحديث

---

**🎉 تهانينا! موقع البادية جاهز للعمل على الاستضافة العادية!**
