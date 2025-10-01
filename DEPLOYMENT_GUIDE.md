# دليل النشر للإنتاج - البادية

## 📋 خطوات النشر

### 1️⃣ إعداد البيئة

```bash
# نسخ ملف الإعدادات
cp production.env.example .env

# تحديث المتغيرات المطلوبة
nano .env
```

### 2️⃣ المتغيرات المطلوب تحديثها

```env
# تحديث النطاق
APP_URL=https://your-domain.com

# تحديث إعدادات البريد الإلكتروني
MAIL_FROM_ADDRESS="noreply@your-domain.com"
MAIL_USERNAME="your-email@gmail.com"
MAIL_PASSWORD="your-app-password"

# تحديث نطاقات Sanctum
SANCTUM_STATEFUL_DOMAINS=your-domain.com,www.your-domain.com
```

### 3️⃣ تشغيل الأوامر المطلوبة

```bash
# تحديث التبعيات
composer install --optimize-autoloader --no-dev

# تشغيل Migrations
php artisan migrate --force

# إنشاء storage link
php artisan storage:link

# تصحيح مسارات الصور
php artisan images:fix-paths

# تنظيف وتخزين مؤقت للإعدادات
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# بناء الأصول
npm run build
```

### 4️⃣ إعدادات الخادم

#### Apache (.htaccess)
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

#### Nginx
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
    fastcgi_index index.php;
    fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    include fastcgi_params;
}
```

### 5️⃣ أذونات الملفات

```bash
# أذونات المجلدات
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# ملكية الملفات
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
```

### 6️⃣ إعدادات قاعدة البيانات

تأكد من أن إعدادات قاعدة البيانات صحيحة:
- `DB_HOST`: عنوان خادم قاعدة البيانات
- `DB_DATABASE`: اسم قاعدة البيانات
- `DB_USERNAME`: اسم المستخدم
- `DB_PASSWORD`: كلمة المرور

### 7️⃣ اختبار النشر

```bash
# اختبار الاتصال بقاعدة البيانات
php artisan tinker --execute="DB::connection()->getPdo();"

# اختبار مسارات الصور
php artisan images:fix-paths

# اختبار التخزين المؤقت
php artisan config:cache
```

### 8️⃣ مراقبة الأخطاء

```bash
# عرض السجلات
tail -f storage/logs/laravel.log

# اختبار إرسال البريد
php artisan tinker --execute="Mail::raw('Test', function(\$m) { \$m->to('test@example.com')->subject('Test'); });"
```

## 🔧 إعدادات إضافية

### SSL/HTTPS
تأكد من تفعيل SSL وتحديث:
- `APP_URL=https://your-domain.com`
- `SESSION_SECURE_COOKIE=true`

### تحسين الأداء
```bash
# تفعيل OPcache في PHP
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=4000

# تفعيل Gzip
# إضافة في .htaccess أو إعدادات Nginx
```

### النسخ الاحتياطي
```bash
# نسخ احتياطي لقاعدة البيانات
mysqldump -u username -p database_name > backup.sql

# نسخ احتياطي للملفات
tar -czf backup.tar.gz storage/app/public/
```

## 🚨 ملاحظات مهمة

1. **أمان**: تأكد من إخفاء ملف `.env`
2. **أداء**: استخدم `php artisan config:cache` في الإنتاج
3. **مراقبة**: راقب سجلات الأخطاء بانتظام
4. **تحديثات**: احتفظ بنسخة احتياطية قبل التحديثات
5. **SSL**: استخدم HTTPS في الإنتاج دائماً

## 📞 الدعم

في حالة وجود مشاكل، تحقق من:
- سجلات Laravel: `storage/logs/laravel.log`
- سجلات الخادم: `/var/log/apache2/error.log` أو `/var/log/nginx/error.log`
- إعدادات قاعدة البيانات
- أذونات الملفات
