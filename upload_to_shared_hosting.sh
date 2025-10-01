#!/bin/bash

# ==============================================
# سكريبت رفع المشروع للاستضافة العادية
# البادية - Shared Hosting Upload Script
# ==============================================

echo "🚀 بدء رفع المشروع للاستضافة العادية..."

# إعدادات الاستضافة (يجب تعديلها حسب استضافتك)
HOST="your-domain.com"
USERNAME="your-username"
PASSWORD="your-password"
REMOTE_PATH="/public_html"
LOCAL_PATH="."

# ألوان للطباعة
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# دالة لطباعة الرسائل الملونة
print_message() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_step() {
    echo -e "${BLUE}[STEP]${NC} $1"
}

# التحقق من وجود rsync
if ! command -v rsync &> /dev/null; then
    print_error "rsync غير مثبت. يرجى تثبيته أولاً."
    echo "Ubuntu/Debian: sudo apt install rsync"
    echo "macOS: brew install rsync"
    exit 1
fi

# التحقق من وجود sshpass (اختياري)
if ! command -v sshpass &> /dev/null; then
    print_warning "sshpass غير مثبت. سيتم طلب كلمة المرور يدوياً."
fi

print_step "1. إعداد المشروع للرفع..."

# إنشاء مجلد مؤقت للرفع
TEMP_DIR="upload_temp"
mkdir -p $TEMP_DIR

# نسخ الملفات المطلوبة
print_message "نسخ الملفات المطلوبة..."

# ملفات Laravel الأساسية
cp -r app $TEMP_DIR/
cp -r bootstrap $TEMP_DIR/
cp -r config $TEMP_DIR/
cp -r database $TEMP_DIR/
cp -r resources $TEMP_DIR/
cp -r routes $TEMP_DIR/
cp -r storage $TEMP_DIR/
cp -r vendor $TEMP_DIR/
cp -r public $TEMP_DIR/

# ملفات الإعدادات
cp .env.production $TEMP_DIR/.env
cp composer.json $TEMP_DIR/
cp composer.lock $TEMP_DIR/
cp artisan $TEMP_DIR/

# ملفات الرفع
cp .htaccess $TEMP_DIR/
cp .htaccess $TEMP_DIR/public/

print_step "2. إعداد أذونات الملفات..."

# إعداد أذونات الملفات
find $TEMP_DIR -type f -exec chmod 644 {} \;
find $TEMP_DIR -type d -exec chmod 755 {} \;
find $TEMP_DIR/storage -type d -exec chmod 775 {} \;
find $TEMP_DIR/bootstrap/cache -type d -exec chmod 775 {} \;

print_step "3. رفع الملفات للاستضافة..."

# إنشاء ملف exclude للملفات غير المطلوبة
cat > rsync_exclude.txt << EOF
*.log
*.tmp
*.swp
*.swo
*~
.git/
.gitignore
README.md
node_modules/
.env.local
.env.development
debug_*.php
test_*.php
SERVER_TROUBLESHOOTING.md
DEPLOYMENT_GUIDE.md
upload_to_shared_hosting.sh
rsync_exclude.txt
EOF

# رفع الملفات باستخدام rsync
if command -v sshpass &> /dev/null; then
    print_message "رفع الملفات باستخدام sshpass..."
    sshpass -p "$PASSWORD" rsync -avz --progress --exclude-from=rsync_exclude.txt $TEMP_DIR/ $USERNAME@$HOST:$REMOTE_PATH/
else
    print_message "رفع الملفات (ستحتاج لإدخال كلمة المرور)..."
    rsync -avz --progress --exclude-from=rsync_exclude.txt $TEMP_DIR/ $USERNAME@$HOST:$REMOTE_PATH/
fi

if [ $? -eq 0 ]; then
    print_message "✅ تم رفع الملفات بنجاح!"
else
    print_error "❌ فشل في رفع الملفات!"
    exit 1
fi

print_step "4. تشغيل أوامر Laravel على السيرفر..."

# أوامر Laravel للتشغيل على السيرفر
cat > server_commands.sh << 'EOF'
#!/bin/bash

echo "🔧 تشغيل أوامر Laravel على السيرفر..."

# الانتقال لمجلد المشروع
cd /home/username/public_html

# تحديث التبعيات
composer install --optimize-autoloader --no-dev

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

# إعداد أذونات الملفات
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

echo "✅ تم تشغيل أوامر Laravel بنجاح!"
EOF

# رفع ملف الأوامر
if command -v sshpass &> /dev/null; then
    sshpass -p "$PASSWORD" scp server_commands.sh $USERNAME@$HOST:$REMOTE_PATH/
    sshpass -p "$PASSWORD" ssh $USERNAME@$HOST "cd $REMOTE_PATH && chmod +x server_commands.sh && ./server_commands.sh"
else
    scp server_commands.sh $USERNAME@$HOST:$REMOTE_PATH/
    ssh $USERNAME@$HOST "cd $REMOTE_PATH && chmod +x server_commands.sh && ./server_commands.sh"
fi

print_step "5. تنظيف الملفات المؤقتة..."

# حذف الملفات المؤقتة
rm -rf $TEMP_DIR
rm -f rsync_exclude.txt
rm -f server_commands.sh

print_message "🎉 تم رفع المشروع للاستضافة بنجاح!"
print_message "🌐 يمكنك الآن الوصول للموقع على: https://$HOST"

echo ""
echo "📝 ملاحظات مهمة:"
echo "1. تأكد من تحديث إعدادات قاعدة البيانات في .env"
echo "2. تأكد من تحديث APP_URL في .env"
echo "3. تأكد من إعداد ADMIN_SECRET في .env"
echo "4. راقب سجلات الأخطاء في storage/logs/laravel.log"
echo ""
echo "🔗 روابط مفيدة:"
echo "- الموقع: https://$HOST"
echo "- لوحة التحكم: https://$HOST/dashboard"
echo "- تسجيل الدخول: https://$HOST/admin/login"
