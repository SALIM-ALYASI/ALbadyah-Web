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
    ];

    protected $casts = [
        'is_active' => 'boolean',
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

