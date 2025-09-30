# API Setup Guide - دليل إعداد API

## تم تفعيل خاصية روابط API بنجاح! 🎉

تم إنشاء نظام API شامل لموقعك السياحي مع الميزات التالية:

## ✅ ما تم إنجازه

### 1. ملفات API Routes
- ✅ `routes/api.php` - جميع روابط API
- ✅ دعم API v1 مع تنظيم واضح للروابط

### 2. API Controllers
- ✅ `GovernorateApiController` - إدارة المحافظات
- ✅ `WilayatApiController` - إدارة الولايات  
- ✅ `TouristSiteApiController` - إدارة المواقع السياحية
- ✅ `TouristServiceApiController` - إدارة الخدمات السياحية
- ✅ `SearchApiController` - البحث المتقدم
- ✅ `VisitApiController` - إحصائيات الزيارات

### 3. API Resources
- ✅ `GovernorateResource` - تنظيم بيانات المحافظات
- ✅ `WilayatResource` - تنظيم بيانات الولايات
- ✅ `TouristSiteResource` - تنظيم بيانات المواقع السياحية
- ✅ `TouristServiceResource` - تنظيم بيانات الخدمات السياحية

### 4. Middleware & Security
- ✅ `CorsMiddleware` - دعم CORS للاستخدام من المتصفحات
- ✅ Laravel Sanctum - نظام المصادقة للـ Admin APIs
- ✅ حماية الروابط الإدارية

### 5. Documentation
- ✅ `API_DOCUMENTATION.md` - دليل شامل لجميع الـ APIs
- ✅ أمثلة عملية للاستخدام
- ✅ شرح جميع المعاملات والاستجابات

## 🚀 كيفية الاستخدام

### 1. تشغيل الخادم
```bash
php artisan serve
```

### 2. اختبار API
```bash
# جلب جميع المحافظات
curl http://localhost:8000/api/v1/governorates

# البحث في المواقع السياحية
curl "http://localhost:8000/api/v1/search?q=مسقط"

# جلب إحصائيات الموقع
curl http://localhost:8000/api/v1/stats
```

### 3. الروابط المتاحة

#### Public APIs (لا تحتاج مصادقة):
- `GET /api/v1/governorates` - جميع المحافظات
- `GET /api/v1/wilayats` - جميع الولايات
- `GET /api/v1/tourist-sites` - جميع المواقع السياحية
- `GET /api/v1/tourist-services` - جميع الخدمات السياحية
- `GET /api/v1/search` - البحث الشامل
- `GET /api/v1/stats` - إحصائيات الموقع
- `POST /api/v1/visits` - تسجيل زيارة

#### Admin APIs (تتطلب مصادقة):
- `POST /api/v1/admin/governorates` - إنشاء محافظة
- `PUT /api/v1/admin/governorates/{id}` - تحديث محافظة
- `DELETE /api/v1/admin/governorates/{id}` - حذف محافظة
- (نفس النمط للولايات والمواقع والخدمات)

## 📋 الميزات المتقدمة

### 1. البحث والفلترة
- بحث نصي في جميع الحقول
- فلترة حسب المحافظة والولاية
- ترتيب متقدم للنتائج
- Pagination للنتائج الكبيرة

### 2. إدارة الصور
- رفع صور متعددة للمواقع السياحية
- حذف الصور الفردية
- URLs آمنة للصور

### 3. إحصائيات متقدمة
- تتبع الزيارات
- إحصائيات حسب البلد والمدينة
- تصدير البيانات (JSON/CSV)
- رسوم بيانية للبيانات

### 4. الأمان
- CORS support للاستخدام من المتصفحات
- Laravel Sanctum للمصادقة
- حماية من SQL Injection
- Validation شامل للبيانات

## 🔧 الخطوات التالية

### 1. إعداد قاعدة البيانات
```bash
# تشغيل migrations
php artisan migrate

# تشغيل seeders (إذا كانت متوفرة)
php artisan db:seed
```

### 2. إعداد Sanctum (للمصادقة)
```bash
# نشر ملفات Sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# تشغيل migrations
php artisan migrate
```

### 3. اختبار APIs
يمكنك استخدام Postman أو أي أداة أخرى لاختبار الـ APIs:
- استيراد ملف Postman Collection (إذا كان متوفراً)
- اختبار جميع الـ endpoints
- التحقق من الاستجابات

## 📚 الملفات المهمة

```
routes/api.php                    # روابط API
app/Http/Controllers/Api/         # API Controllers
app/Http/Resources/               # API Resources
app/Http/Middleware/CorsMiddleware.php  # CORS Middleware
API_DOCUMENTATION.md             # دليل API الشامل
```

## 🎯 أمثلة سريعة

### جلب محافظة مع ولاياتها:
```bash
curl "http://localhost:8000/api/v1/governorates/1?include=wilayats"
```

### البحث في المواقع السياحية:
```bash
curl "http://localhost:8000/api/v1/search/sites?q=قصر&governorate_id=1"
```

### تسجيل زيارة:
```bash
curl -X POST "http://localhost:8000/api/v1/visits" \
  -H "Content-Type: application/json" \
  -d '{"ip_address":"192.168.1.1","country":"Oman","city":"Muscat"}'
```

## 🆘 الدعم

إذا واجهت أي مشاكل:
1. تحقق من logs في `storage/logs/`
2. تأكد من إعدادات قاعدة البيانات
3. تحقق من صلاحيات الملفات
4. راجع `API_DOCUMENTATION.md` للتفاصيل الكاملة

---

**تم إنشاء نظام API متكامل وجاهز للاستخدام! 🚀**
