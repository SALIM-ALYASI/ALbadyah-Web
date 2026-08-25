<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TouristService extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'slug',
        'website_url',
        'image_url',
        'image_path',
        'location_image_path',
        'location_image_url',
        'governorate_id',
        'wilayat_id',
        'service_type_id',
        'data_source_id',
        'source_url',
        'source_name',
        'source_type',
        'external_id',
        'latitude',
        'longitude',
        'phone',
        'whatsapp',
        'description_ar',
        'description_en',
        'verification_status',
        'needs_review_fields',
        'last_verified_at',
        'ai_generated',
        'confidence_score',
        'source_trust_score',
        'ai_confidence',
        'name_ar_verified',
        'description_ar_generated',
        'name_ar_source',
        'coordinates_source',
        'is_tourism_candidate',
        'excluded_reason',
        'collected_at',
        'source_last_checked_at',
        'review_requested_at',
        'collector_name',
        'collector_version',
        'is_active',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'ai_generated' => 'boolean',
        'is_tourism_candidate' => 'boolean',
        'name_ar_verified' => 'boolean',
        'description_ar_generated' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'confidence_score' => 'decimal:2',
        'source_trust_score' => 'decimal:2',
        'ai_confidence' => 'decimal:2',
        'needs_review_fields' => 'array',
        'last_verified_at' => 'datetime',
        'review_requested_at' => 'datetime',
        'collected_at' => 'datetime',
        'source_last_checked_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    protected $attributes = [
        'is_active' => true,
        'ai_generated' => false,
        'verification_status' => 'approved',
        'is_tourism_candidate' => true,
    ];

    // نوع الخدمة (من جدول service_types)
    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id');
    }

    public function dataSource()
    {
        return $this->belongsTo(DataSource::class);
    }

    public function images()
    {
        return $this->hasMany(TouristServiceImage::class);
    }

    public function verificationLogs()
    {
        return $this->morphMany(VerificationLog::class, 'recordable');
    }

    public function scopeApproved($query)
    {
        return $query->where('verification_status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    public function scopeNeedsReview($query)
    {
        return $query->where('verification_status', 'needs_review');
    }

    public function scopePubliclyVisible($query)
    {
        return $query->where('is_active', true)
            ->where('verification_status', 'approved')
            ->where('is_tourism_candidate', true);
    }

    // المحافظة (اختياري)
    public function governorate()
    {
        return $this->belongsTo(Governorate::class, 'governorate_id');
    }

    // الولاية (اختياري)
    public function wilayat()
    {
        return $this->belongsTo(Wilayat::class, 'wilayat_id');
    }

    /**
     * الحصول على رابط صورة الموقع
     */
    public function getLocationImageUrlAttribute()
    {
        return \App\Helpers\ImageHelper::getImageUrl(
            $this->attributes['location_image_path'] ?? null,
            $this->attributes['location_image_url'] ?? null
        );
    }

    /**
     * التحقق من وجود صورة الموقع
     */
    public function getHasLocationImageAttribute()
    {
        return \App\Helpers\ImageHelper::hasImage(
            $this->attributes['location_image_path'] ?? null,
            $this->attributes['location_image_url'] ?? null
        );
    }

    /**
     * الحصول على معلومات صورة الموقع
     */
    public function getLocationImageInfoAttribute()
    {
        return \App\Helpers\ImageHelper::getImageInfo(
            $this->attributes['location_image_path'] ?? null,
            $this->attributes['location_image_url'] ?? null
        );
    }

    /**
     * الحصول على رابط صورة الخدمة (يسقط تلقائيًا لصورة تصنيف عامة عند غياب صورة حقيقية)
     */
    public function getImageUrlAttribute()
    {
        return \App\Helpers\ImageHelper::getImageUrl(
            $this->attributes['image_path'] ?? null,
            $this->attributes['image_url'] ?? null,
            $this->placeholderImagePath()
        );
    }

    /**
     * التحقق من وجود صورة الخدمة
     */
    public function getHasImageAttribute()
    {
        return \App\Helpers\ImageHelper::hasImage(
            $this->attributes['image_path'] ?? null,
            $this->attributes['image_url'] ?? null
        );
    }

    /**
     * هل الصورة المعروضة حاليًا رسم توضيحي عام للتصنيف (لا صورة حقيقية للخدمة بعد)
     */
    public function getIsPlaceholderImageAttribute()
    {
        return !$this->has_image;
    }

    private function placeholderImagePath(): string
    {
        $file = $this->serviceType?->placeholder_image;

        return $file ? "images/service-placeholders/{$file}" : 'images/default-placeholder.jpg';
    }

    /**
     * الحصول على معلومات صورة الخدمة
     */
    public function getImageInfoAttribute()
    {
        return \App\Helpers\ImageHelper::getImageInfo(
            $this->attributes['image_path'] ?? null,
            $this->attributes['image_url'] ?? null
        );
    }

    /**
     * رابط الاتجاهات إلى الخدمة في خرائط جوجل
     */
    public function getMapsUrlAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return "https://www.google.com/maps/search/?api=1&query={$this->latitude},{$this->longitude}";
        }

        return 'https://www.google.com/maps/search/' . urlencode($this->name_en ?: $this->name_ar);
    }

    /**
     * إنشاء slug تلقائياً عند إنشاء أو تحديث الخدمة
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = static::generateUniqueSlug($service->name_ar);
            }
        });

        static::updating(function ($service) {
            if ($service->isDirty('name_ar') && empty($service->slug)) {
                $service->slug = static::generateUniqueSlug($service->name_ar);
            }
        });
    }

    /**
     * إنشاء slug فريد من النص العربي
     */
    public static function generateUniqueSlug($text)
    {
        // تحويل النص العربي إلى slug
        $slug = static::arabicToSlug($text);
        
        // التأكد من أن الـ slug فريد
        $originalSlug = $slug;
        $counter = 1;
        
        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * تحويل النص العربي إلى slug
     */
    private static function arabicToSlug($text)
    {
        // إزالة المسافات الزائدة وتحويلها إلى شرطات
        $text = preg_replace('/\s+/', '-', trim($text));
        
        // إزالة الأحرف الخاصة
        $text = preg_replace('/[^\p{L}\p{N}\-]/u', '', $text);
        
        // تحويل إلى أحرف صغيرة
        $text = strtolower($text);
        
        // إزالة الشرطات المتعددة
        $text = preg_replace('/-+/', '-', $text);
        
        // إزالة الشرطات من البداية والنهاية
        $text = trim($text, '-');
        
        return $text;
    }

    /**
     * البحث عن خدمة باستخدام slug
     */
    public static function findBySlug($slug)
    {
        return static::where('slug', $slug)->first();
    }
}

