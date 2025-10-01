<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TouristSiteNew extends Model
{
    use HasFactory;

    protected $table = 'tourist_site_news';

    protected $fillable = [
        'name_ar',
        'name_en',
        'slug',
        'description_ar',
        'description_en',
        'location',
        'website_url',
        'phone',
        'email',
        'latitude',
        'longitude',
        'governorate_id',
        'wilayat_id',
        'is_active',
        'featured_image',
        'meta_title_ar',
        'meta_title_en',
        'meta_description_ar',
        'meta_description_en',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * إنشاء slug تلقائياً عند الحفظ
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name_en ?: $model->name_ar);
            }
        });
        
        static::updating(function ($model) {
            if ($model->isDirty(['name_ar', 'name_en']) && empty($model->slug)) {
                $model->slug = Str::slug($model->name_en ?: $model->name_ar);
            }
        });
    }

    /**
     * كل موقع يتبع محافظة
     */
    public function governorate()
    {
        return $this->belongsTo(Governorate::class, 'governorate_id');
    }

    /**
     * كل موقع يتبع ولاية
     */
    public function wilayat()
    {
        return $this->belongsTo(Wilayat::class, 'wilayat_id');
    }

    /**
     * لكل موقع عدة صور
     */
    public function images()
    {
        return $this->hasMany(TouristImageNew::class, 'tourist_site_id');
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
        $firstImage = $this->images()->first();
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

    /**
     * البحث في المواقع السياحية
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name_ar', 'like', "%{$search}%")
              ->orWhere('name_en', 'like', "%{$search}%")
              ->orWhere('description_ar', 'like', "%{$search}%")
              ->orWhere('description_en', 'like', "%{$search}%");
        });
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
}