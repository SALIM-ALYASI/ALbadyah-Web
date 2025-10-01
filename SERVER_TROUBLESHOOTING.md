# دليل حل مشاكل السيرفر - البادية

## 🚨 مشكلة: صفحة tourist-sites-new لا تفتح

### 📋 خطوات التشخيص

#### 1️⃣ **فحص أساسي على السيرفر**

```bash
# رفع ملف debug_server_issues.php إلى السيرفر
# ثم فتح: https://al-badyah.com/debug_server_issues.php

# أو تشغيل عبر SSH
php debug_laravel.php
```

#### 2️⃣ **فحص ملف .env على السيرفر**

تأكد من وجود هذه المتغيرات:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://al-badyah.com
ADMIN_SECRET=admin123
```

#### 3️⃣ **فحص قاعدة البيانات**

```bash
# تشغيل migrations
php artisan migrate --force

# فحص الجداول
php artisan tinker --execute="DB::table('tourist_site_news')->count();"
```

#### 4️⃣ **تنظيف التخزين المؤقت**

```bash
# تنظيف كامل
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# إعادة إنشاء التخزين المؤقت
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 5️⃣ **فحص أذونات الملفات**

```bash
# إعداد أذونات صحيحة
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
```

### 🔧 الحلول المحتملة

#### ✅ **الحل 1: مشكلة في Middleware**

إذا كانت المشكلة في `admin.auth` middleware:

```bash
# فحص middleware
php artisan route:list --name=tourist-sites-new

# إذا كان middleware لا يعمل، جرب:
php artisan config:clear
php artisan route:clear
```

#### ✅ **الحل 2: مشكلة في Session**

```bash
# فحص إعدادات session
grep SESSION_ .env

# تأكد من أن session driver صحيح
SESSION_DRIVER=database
SESSION_LIFETIME=120
```

#### ✅ **الحل 3: مشكلة في Routes**

```bash
# فحص routes
php artisan route:list | grep tourist-sites-new

# إعادة تحميل routes
php artisan route:cache
```

#### ✅ **الحل 4: مشكلة في قاعدة البيانات**

```bash
# فحص الاتصال
php artisan tinker --execute="DB::connection()->getPdo();"

# تشغيل migrations
php artisan migrate --force

# فحص الجداول المطلوبة
php artisan tinker --execute="
echo 'tourist_site_news: ' . DB::table('tourist_site_news')->count() . PHP_EOL;
echo 'tourist_image_news: ' . DB::table('tourist_image_news')->count() . PHP_EOL;
"
```

#### ✅ **الحل 5: مشكلة في Views**

```bash
# فحص وجود ملفات views
ls -la resources/views/admin/tourist-sites-new/

# إذا كانت مفقودة، انسخها من المشروع المحلي
```

#### ✅ **الحل 6: مشكلة في Controller**

```bash
# فحص وجود Controller
ls -la app/Http/Controllers/TouristSiteNewController.php

# فحص محتوى Controller
head -20 app/Http/Controllers/TouristSiteNewController.php
```

### 🔍 تشخيص متقدم

#### **فحص سجلات الأخطاء**

```bash
# سجلات Laravel
tail -f storage/logs/laravel.log

# سجلات Apache/Nginx
tail -f /var/log/apache2/error.log
# أو
tail -f /var/log/nginx/error.log

# سجلات PHP
tail -f /var/log/php_errors.log
```

#### **فحص إعدادات الخادم**

```bash
# فحص PHP
php -v
php -m | grep -E "(pdo|mysql|mbstring|openssl)"

# فحص إعدادات PHP
php -i | grep -E "(memory_limit|max_execution_time|upload_max_filesize)"
```

### 🚀 حلول سريعة

#### **1. إعادة تشغيل الخدمات**

```bash
# إعادة تشغيل Apache/Nginx
sudo systemctl restart apache2
# أو
sudo systemctl restart nginx

# إعادة تشغيل PHP-FPM
sudo systemctl restart php8.2-fpm
```

#### **2. إعادة نشر المشروع**

```bash
# نسخ ملفات جديدة
git pull origin main

# تحديث التبعيات
composer install --optimize-autoloader --no-dev

# تنظيف وإعادة بناء
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### **3. فحص DNS والـ SSL**

```bash
# فحص DNS
nslookup al-badyah.com

# فحص SSL
curl -I https://al-badyah.com

# فحص redirect
curl -L https://al-badyah.com/dashboard/tourist-sites-new
```

### 📞 خطوات الطوارئ

إذا لم تعمل الحلول أعلاه:

1. **فحص ملف .htaccess**
2. **فحص إعدادات Apache/Nginx**
3. **فحص إعدادات PHP**
4. **فحص أذونات الملفات**
5. **فحص سجلات الأخطاء**

### 📝 ملاحظات مهمة

- تأكد من أن `APP_URL` في .env يطابق النطاق الفعلي
- تأكد من أن `ADMIN_SECRET` محدد في .env
- تأكد من أن قاعدة البيانات تعمل
- تأكد من أن جميع الملفات موجودة
- تأكد من أن أذونات الملفات صحيحة

### 🔗 روابط مفيدة

- [Laravel Deployment](https://laravel.com/docs/deployment)
- [Laravel Troubleshooting](https://laravel.com/docs/troubleshooting)
- [PHP Configuration](https://www.php.net/manual/en/configuration.php)
