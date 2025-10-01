<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Governorate extends Model
{
    use HasFactory;

    // اسم الجدول (Laravel يتعرف عليه تلقائيًا لأنه جمع اسم الموديل)
    protected $table = 'governorates';

    // الحقول المسموح تعبئتها
    protected $fillable = [
        'name_ar',
        'name_en',
        'slug',
        'website_url',
        'image_url',
        'image_path',
    ];

    public function wilayats()
    {
        return $this->hasMany(Wilayat::class, 'governorate_id');
    }
    public function touristSites()
    {
        return $this->hasMany(TouristSite::class, 'governorate_id');
    }
    public function touristServices()
    {
        return $this->hasMany(TouristService::class, 'governorate_id');
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

        static::creating(function ($governorate) {
            if (empty($governorate->slug)) {
                $governorate->slug = static::generateUniqueSlug($governorate->name_ar);
            }
        });

        static::updating(function ($governorate) {
            if ($governorate->isDirty('name_ar') && empty($governorate->slug)) {
                $governorate->slug = static::generateUniqueSlug($governorate->name_ar);
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
     * البحث عن المحافظة باستخدام slug
     */
    public static function findBySlug($slug)
    {
        return static::where('slug', $slug)->first();
    }
}
