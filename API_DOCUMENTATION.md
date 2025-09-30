# API Documentation - الموقع السياحي

## نظرة عامة
هذا الموقع يوفر API شامل للوصول إلى البيانات السياحية في عُمان، بما في ذلك المحافظات والولايات والمواقع السياحية والخدمات.

## Base URL
```
http://your-domain.com/api/v1
```

## Authentication
- **Public APIs**: لا تحتاج إلى مصادقة
- **Admin APIs**: تحتاج إلى Bearer Token من Laravel Sanctum

## Headers المطلوبة
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer {token} (للـ Admin APIs فقط)
```

## Public APIs

### 1. المحافظات (Governorates)

#### جلب جميع المحافظات
```http
GET /api/v1/governorates
```

**Query Parameters:**
- `search` (string): البحث في أسماء المحافظات
- `sort` (string): الترتيب (name_ar, sites, services, wilayats)
- `per_page` (int): عدد النتائج في الصفحة (افتراضي: 15)

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name_ar": "مسقط",
            "name_en": "Muscat",
            "description_ar": "وصف المحافظة",
            "description_en": "Governorate description",
            "coordinates": {
                "lat": 23.5880,
                "lng": 58.3829
            },
            "stats": {
                "wilayats_count": 6,
                "tourist_sites_count": 25,
                "tourist_services_count": 15
            },
            "created_at": "2024-01-01 00:00:00",
            "updated_at": "2024-01-01 00:00:00"
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 3,
        "per_page": 15,
        "total": 45
    }
}
```

#### جلب محافظة محددة
```http
GET /api/v1/governorates/{id}
```

#### جلب ولايات محافظة محددة
```http
GET /api/v1/governorates/{id}/wilayats
```

#### جلب المواقع السياحية لمحافظة محددة
```http
GET /api/v1/governorates/{id}/tourist-sites
```

#### جلب الخدمات السياحية لمحافظة محددة
```http
GET /api/v1/governorates/{id}/tourist-services
```

### 2. الولايات (Wilayats)

#### جلب جميع الولايات
```http
GET /api/v1/wilayats
```

**Query Parameters:**
- `search` (string): البحث في أسماء الولايات
- `governorate_id` (int): فلترة حسب المحافظة
- `sort` (string): الترتيب (name_ar, sites, services)
- `per_page` (int): عدد النتائج في الصفحة

#### جلب ولاية محددة
```http
GET /api/v1/wilayats/{id}
```

#### جلب المواقع السياحية لولاية محددة
```http
GET /api/v1/wilayats/{id}/tourist-sites
```

#### جلب الخدمات السياحية لولاية محددة
```http
GET /api/v1/wilayats/{id}/tourist-services
```

### 3. المواقع السياحية (Tourist Sites)

#### جلب جميع المواقع السياحية
```http
GET /api/v1/tourist-sites
```

**Query Parameters:**
- `search` (string): البحث في أسماء المواقع
- `governorate_id` (int): فلترة حسب المحافظة
- `wilayat_id` (int): فلترة حسب الولاية
- `sort` (string): الترتيب (name_ar, name_en, governorate, created_at)
- `per_page` (int): عدد النتائج في الصفحة

#### جلب موقع سياحي محدد
```http
GET /api/v1/tourist-sites/{id}
```

#### جلب صور موقع سياحي محدد
```http
GET /api/v1/tourist-sites/{id}/images
```

### 4. الخدمات السياحية (Tourist Services)

#### جلب جميع الخدمات السياحية
```http
GET /api/v1/tourist-services
```

**Query Parameters:**
- `search` (string): البحث في أسماء الخدمات
- `service_type_id` (int): فلترة حسب نوع الخدمة
- `governorate_id` (int): فلترة حسب المحافظة
- `wilayat_id` (int): فلترة حسب الولاية
- `sort` (string): الترتيب (name_ar, name_en, service_type, created_at)
- `per_page` (int): عدد النتائج في الصفحة

#### جلب خدمة سياحية محددة
```http
GET /api/v1/tourist-services/{id}
```

### 5. البحث (Search)

#### البحث الشامل
```http
GET /api/v1/search?q={query}
```

**Query Parameters:**
- `q` (string): نص البحث
- `type` (string): نوع البحث (all, sites, services)
- `governorate_id` (int): فلترة حسب المحافظة
- `wilayat_id` (int): فلترة حسب الولاية
- `per_page` (int): عدد النتائج في الصفحة

#### البحث في المواقع السياحية فقط
```http
GET /api/v1/search/sites?q={query}
```

#### البحث في الخدمات السياحية فقط
```http
GET /api/v1/search/services?q={query}
```

### 6. إحصائيات الزيارات

#### تسجيل زيارة جديدة
```http
POST /api/v1/visits
```

**Request Body:**
```json
{
    "ip_address": "192.168.1.1",
    "user_agent": "Mozilla/5.0...",
    "page_url": "https://example.com/page",
    "referrer": "https://google.com",
    "country": "Oman",
    "city": "Muscat"
}
```

#### جلب إحصائيات الزيارات
```http
GET /api/v1/visits/stats
```

**Query Parameters:**
- `period` (int): عدد الأيام (افتراضي: 30)

#### جلب إجمالي الزيارات
```http
GET /api/v1/visits/total
```

### 7. الإحصائيات العامة

#### جلب إحصائيات الموقع
```http
GET /api/v1/stats
```

## Admin APIs (تتطلب مصادقة)

### 1. إدارة المحافظات

#### إنشاء محافظة جديدة
```http
POST /api/v1/admin/governorates
```

**Request Body:**
```json
{
    "name_ar": "مسقط",
    "name_en": "Muscat",
    "description_ar": "وصف المحافظة",
    "description_en": "Governorate description",
    "latitude": 23.5880,
    "longitude": 58.3829
}
```

#### تحديث محافظة
```http
PUT /api/v1/admin/governorates/{id}
```

#### حذف محافظة
```http
DELETE /api/v1/admin/governorates/{id}
```

### 2. إدارة الولايات

#### إنشاء ولاية جديدة
```http
POST /api/v1/admin/wilayats
```

**Request Body:**
```json
{
    "name_ar": "مسقط",
    "name_en": "Muscat",
    "description_ar": "وصف الولاية",
    "description_en": "Wilayat description",
    "governorate_id": 1,
    "latitude": 23.5880,
    "longitude": 58.3829
}
```

#### تحديث ولاية
```http
PUT /api/v1/admin/wilayats/{id}
```

#### حذف ولاية
```http
DELETE /api/v1/admin/wilayats/{id}
```

### 3. إدارة المواقع السياحية

#### إنشاء موقع سياحي جديد
```http
POST /api/v1/admin/tourist-sites
```

**Request Body:**
```json
{
    "name_ar": "قصر العلم",
    "name_en": "Al Alam Palace",
    "description_ar": "وصف الموقع",
    "description_en": "Site description",
    "governorate_id": 1,
    "wilayat_id": 1,
    "latitude": 23.6141,
    "longitude": 58.5921,
    "address_ar": "العنوان بالعربية",
    "address_en": "Address in English",
    "phone": "+968 1234 5678",
    "email": "info@example.com",
    "website": "https://example.com",
    "opening_hours": "9:00 AM - 5:00 PM",
    "entry_fee": 5.000
}
```

#### تحديث موقع سياحي
```http
PUT /api/v1/admin/tourist-sites/{id}
```

#### حذف موقع سياحي
```http
DELETE /api/v1/admin/tourist-sites/{id}
```

#### إضافة صور لموقع سياحي
```http
POST /api/v1/admin/tourist-sites/{id}/images
```

**Request Body (multipart/form-data):**
```
images[]: [file1, file2, ...]
alt_texts[]: ["وصف الصورة 1", "وصف الصورة 2", ...]
```

#### حذف صورة من موقع سياحي
```http
DELETE /api/v1/admin/tourist-sites/{id}/images/{imageId}
```

### 4. إدارة الخدمات السياحية

#### إنشاء خدمة سياحية جديدة
```http
POST /api/v1/admin/tourist-services
```

**Request Body:**
```json
{
    "name_ar": "فندق شيراتون",
    "name_en": "Sheraton Hotel",
    "description_ar": "وصف الخدمة",
    "description_en": "Service description",
    "service_type_id": 1,
    "governorate_id": 1,
    "wilayat_id": 1,
    "latitude": 23.6141,
    "longitude": 58.5921,
    "address_ar": "العنوان بالعربية",
    "address_en": "Address in English",
    "phone": "+968 1234 5678",
    "email": "info@example.com",
    "website": "https://example.com",
    "opening_hours": "24/7",
    "price_range": "$$$",
    "rating": 4.5
}
```

#### تحديث خدمة سياحية
```http
PUT /api/v1/admin/tourist-services/{id}
```

#### حذف خدمة سياحية
```http
DELETE /api/v1/admin/tourist-services/{id}
```

### 5. إحصائيات الإدمن

#### جلب إحصائيات مفصلة
```http
GET /api/v1/admin/visits/stats
```

#### تصدير بيانات الزيارات
```http
GET /api/v1/admin/visits/export?format=csv&period=30
```

## Response Format

### Success Response
```json
{
    "success": true,
    "data": { ... },
    "message": "تمت العملية بنجاح"
}
```

### Error Response
```json
{
    "success": false,
    "message": "رسالة الخطأ",
    "error": "تفاصيل الخطأ"
}
```

### Pagination
```json
{
    "pagination": {
        "current_page": 1,
        "last_page": 3,
        "per_page": 15,
        "total": 45
    }
}
```

## Status Codes

- `200` - OK
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

## Rate Limiting

- **Public APIs**: 1000 requests per hour per IP
- **Admin APIs**: 500 requests per hour per authenticated user

## CORS Support

جميع الـ APIs تدعم CORS للاستخدام من المتصفحات:
- `Access-Control-Allow-Origin: *`
- `Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS`
- `Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept, Origin`

## Examples

### جلب جميع المحافظات مع البحث
```bash
curl -X GET "http://your-domain.com/api/v1/governorates?search=مسقط&sort=sites&per_page=10" \
  -H "Accept: application/json"
```

### البحث في المواقع السياحية
```bash
curl -X GET "http://your-domain.com/api/v1/search/sites?q=قصر&governorate_id=1" \
  -H "Accept: application/json"
```

### تسجيل زيارة جديدة
```bash
curl -X POST "http://your-domain.com/api/v1/visits" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "ip_address": "192.168.1.1",
    "user_agent": "Mozilla/5.0...",
    "page_url": "https://example.com",
    "country": "Oman",
    "city": "Muscat"
  }'
```

## Support

للحصول على المساعدة أو الإبلاغ عن مشاكل، يرجى التواصل مع فريق التطوير.
