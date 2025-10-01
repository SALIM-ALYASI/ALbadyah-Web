<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Wilayat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'slug',
        'website_url',
        'image_url',
        'image_path',
        'governorate_id',
    ];

    /**
     * كل ولاية تتبع محافظة
     */
    public function governorate()
    {
        return $this->belongsTo(Governorate::class, 'governorate_id');
    }
    public function touristSites()
    {
        return $this->hasMany(TouristSite::class, 'wilayat_id');
    }
    public function touristServices()
    {
        return $this->hasMany(TouristService::class, 'wilayat_id');
    }

    /**
     * الحصول على رابط الصورة
     */
    public function getImageUrlAttribute()
    {
        return \App\Helpers\ImageHelper::getImageUrl(
            $this->attributes['image_path'] ?? null, 
            $this->attributes['image_url'] ?? null
        );
    }

    /**
     * التحقق من وجود الصورة
     */
    public function getHasImageAttribute()
    {
        return \App\Helpers\ImageHelper::hasImage(
            $this->attributes['image_path'] ?? null, 
            $this->attributes['image_url'] ?? null
        );
    }

    /**
     * الحصول على معلومات الصورة
     */
    public function getImageInfoAttribute()
    {
        return \App\Helpers\ImageHelper::getImageInfo(
            $this->attributes['image_path'] ?? null, 
            $this->attributes['image_url'] ?? null
        );
    }

    /**
     * إنشاء slug تلقائياً عند الحفظ
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($wilayat) {
            if (empty($wilayat->slug)) {
                $wilayat->slug = static::generateUniqueSlug($wilayat->name_ar);
            }
        });

        static::updating(function ($wilayat) {
            if ($wilayat->isDirty('name_ar') && empty($wilayat->slug)) {
                $wilayat->slug = static::generateUniqueSlug($wilayat->name_ar);
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
     * البحث عن الولاية باستخدام slug
     */
    public static function findBySlug($slug)
    {
        return static::where('slug', $slug)->first();
    }
}
