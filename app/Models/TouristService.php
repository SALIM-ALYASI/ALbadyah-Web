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
    ];

    // نوع الخدمة (من جدول service_types)
    public function serviceType()
    {
        return $this->belongsTo(\App\Models\ServiceType::class, 'service_type_id');
    }

    // المحافظة (اختياري)
    public function governorate()
    {
        return $this->belongsTo(\App\Models\Governorate::class, 'governorate_id');
    }

    // الولاية (اختياري)
    public function wilayat()
    {
        return $this->belongsTo(\App\Models\Wilayat::class, 'wilayat_id');
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
     * الحصول على رابط صورة الخدمة
     */
    public function getImageUrlAttribute()
    {
        return \App\Helpers\ImageHelper::getImageUrl(
            $this->attributes['image_path'] ?? null,
            $this->attributes['image_url'] ?? null
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

