#!/bin/bash

# ==============================================
# سكريبت النشر التلقائي - البادية
# ==============================================

echo "🚀 بدء عملية النشر..."

# التحقق من وجود composer
if ! command -v composer &> /dev/null; then
    echo "❌ Composer غير مثبت. يرجى تثبيته أولاً."
    exit 1
fi

# التحقق من وجود PHP
if ! command -v php &> /dev/null; then
    echo "❌ PHP غير مثبت. يرجى تثبيته أولاً."
    exit 1
fi

echo "✅ التحقق من المتطلبات مكتمل"

# نسخ ملف الإعدادات إذا لم يكن موجوداً
if [ ! -f .env ]; then
    echo "📋 نسخ ملف الإعدادات..."
    cp production.env.example .env
    echo "⚠️  يرجى تحديث ملف .env قبل المتابعة"
    echo "📝 المتغيرات المطلوب تحديثها:"
    echo "   - APP_URL"
    echo "   - MAIL_FROM_ADDRESS"
    echo "   - SANCTUM_STATEFUL_DOMAINS"
    read -p "هل قمت بتحديث ملف .env؟ (y/n): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        echo "❌ يرجى تحديث ملف .env أولاً"
        exit 1
    fi
fi

# تحديث التبعيات
echo "📦 تحديث التبعيات..."
composer install --optimize-autoloader --no-dev

# تشغيل migrations
echo "🗄️ تشغيل Migrations..."
php artisan migrate --force

# إنشاء storage link
echo "🔗 إنشاء storage link..."
php artisan storage:link

# تصحيح مسارات الصور
echo "🖼️ تصحيح مسارات الصور..."
php artisan images:fix-paths

# تنظيف التخزين المؤقت
echo "🧹 تنظيف التخزين المؤقت..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# إنشاء التخزين المؤقت للإنتاج
echo "⚡ إنشاء التخزين المؤقت للإنتاج..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# بناء الأصول
echo "🎨 بناء الأصول..."
if command -v npm &> /dev/null; then
    npm run build
else
    echo "⚠️  npm غير مثبت، سيتم تخطي بناء الأصول"
fi

# إعداد أذونات الملفات
echo "🔐 إعداد أذونات الملفات..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# اختبار الاتصال بقاعدة البيانات
echo "🔍 اختبار الاتصال بقاعدة البيانات..."
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database connection successful'; } catch(Exception \$e) { echo 'Database connection failed: ' . \$e->getMessage(); }" 2>/dev/null

if [ $? -eq 0 ]; then
    echo "✅ الاتصال بقاعدة البيانات ناجح"
else
    echo "❌ فشل الاتصال بقاعدة البيانات"
fi

# عرض معلومات النشر
echo ""
echo "🎉 تم النشر بنجاح!"
echo "📋 ملخص النشر:"
echo "   - تم تحديث التبعيات"
echo "   - تم تشغيل Migrations"
echo "   - تم إنشاء storage link"
echo "   - تم تصحيح مسارات الصور"
echo "   - تم تنظيف التخزين المؤقت"
echo "   - تم إنشاء التخزين المؤقت للإنتاج"
echo "   - تم بناء الأصول (إذا كان npm متاحاً)"
echo "   - تم إعداد أذونات الملفات"
echo ""
echo "🌐 يمكنك الآن الوصول للموقع على: $(grep APP_URL .env | cut -d '=' -f2)"
echo ""
echo "📝 ملاحظات مهمة:"
echo "   - تأكد من إعداد SSL/HTTPS"
echo "   - راقب سجلات الأخطاء: storage/logs/laravel.log"
echo "   - احتفظ بنسخة احتياطية من قاعدة البيانات"
echo ""
