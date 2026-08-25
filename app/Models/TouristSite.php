<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TouristSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'slug',
        'description_ar',
        'description_en',
        'location',
        'website_url',
        'governorate_id',
        'wilayat_id',
        'is_active',
        'featured_image',
        'tourist_site_category_id',
        'data_source_id',
        'source_url',
        'source_name',
        'source_type',
        'external_id',
        'latitude',
        'longitude',
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
        'collected_at' => 'datetime',
        'source_last_checked_at' => 'datetime',
        'review_requested_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    /**
     * قيم افتراضية على مستوى PHP تطابق افتراضيات قاعدة البيانات، حتى تنعكس
     * فورًا على الكائن بعد create() دون الحاجة لإعادة الجلب من القاعدة.
     */
    protected $attributes = [
        'is_active' => true,
        'ai_generated' => false,
        'verification_status' => 'approved',
        'is_tourism_candidate' => true,
    ];

    // كل موقع يتبع محافظة
    public function governorate()
    {
        return $this->belongsTo(Governorate::class, 'governorate_id');
    }

    // كل موقع يتبع ولاية
    public function wilayat()
    {
        return $this->belongsTo(Wilayat::class, 'wilayat_id');
    }

    // لكل موقع عدة صور
    public function images()
    {
        return $this->hasMany(TouristImage::class, 'tourist_site_id');
    }

    public function category()
    {
        return $this->belongsTo(TouristSiteCategory::class, 'tourist_site_category_id');
    }

    public function dataSource()
    {
        return $this->belongsTo(DataSource::class);
    }

    public function verificationLogs()
    {
        return $this->morphMany(VerificationLog::class, 'recordable');
    }

    /**
     * فلترة السجلات المعتمدة فقط (الجاهزة للعرض العام).
     */
    public function scopeApproved($query)
    {
        return $query->where('verification_status', 'approved');
    }

    /**
     * فلترة السجلات المعلقة بانتظار مراجعة الأدمن.
     */
    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    public function scopeNeedsReview($query)
    {
        return $query->where('verification_status', 'needs_review');
    }

    /**
     * ما يظهر فعليًا في الموقع العام: نشِط + معتمد.
     */
    public function scopePubliclyVisible($query)
    {
        return $query->where('is_active', true)
            ->where('verification_status', 'approved')
            ->where('is_tourism_candidate', true);
    }

    /**
     * إنشاء slug تلقائياً عند الحفظ
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($touristSite) {
            if (empty($touristSite->slug)) {
                $touristSite->slug = static::generateUniqueSlug($touristSite->name_ar);
            }
        });

        static::updating(function ($touristSite) {
            if ($touristSite->isDirty('name_ar') && empty($touristSite->slug)) {
                $touristSite->slug = static::generateUniqueSlug($touristSite->name_ar);
            }
        });
    }

    /**
     * إنشاء slug فريد
     */
    public static function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * البحث عن الموقع السياحي باستخدام slug
     */
    public static function findBySlug($slug)
    {
        return static::where('slug', $slug)->first();
    }

    /**
     * فلترة المواقع النشطة فقط
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * فلترة المواقع حسب المحافظة
     */
    public function scopeByGovernorate($query, $governorateId)
    {
        return $query->where('governorate_id', $governorateId);
    }

    /**
     * فلترة المواقع حسب الولاية
     */
    public function scopeByWilayat($query, $wilayatId)
    {
        return $query->where('wilayat_id', $wilayatId);
    }

    /**
     * الحصول على الصورة المميزة
     */
    public function getFeaturedImageAttribute($value)
    {
        if ($value) {
            return \App\Helpers\ImageHelper::getImageUrl($value, null);
        }
        
        // إذا لم تكن هناك صورة مميزة، احصل على أول صورة
        $firstImage = $this->images()->featured()->first() ?: $this->images()->first();
        return $firstImage ? $firstImage->image_url : asset('images/default-tourist-site.jpg');
    }

    /**
     * رابط الاتجاهات إلى الموقع في خرائط جوجل
     */
    public function getMapsUrlAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return "https://www.google.com/maps/search/?api=1&query={$this->latitude},{$this->longitude}";
        }

        return 'https://www.google.com/maps/search/' . urlencode($this->name_en ?: $this->name_ar);
    }

    /**
     * الحصول على اسم الموقع حسب اللغة
     */
    public function getName($lang = 'ar')
    {
        return $lang === 'en' ? $this->name_en : $this->name_ar;
    }

    /**
     * الحصول على وصف الموقع حسب اللغة
     */
    public function getDescription($lang = 'ar')
    {
        return $lang === 'en' ? $this->description_en : $this->description_ar;
    }
}

