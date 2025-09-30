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
    ];

    // كل موقع يتبع محافظة
    public function governorate()
    {
        return $this->belongsTo(\App\Models\Governorate::class, 'governorate_id');
    }

    // كل موقع يتبع ولاية
    public function wilayat()
    {
        return $this->belongsTo(\App\Models\Wilayat::class, 'wilayat_id');
    }

    // لكل موقع عدة صور
    public function images()
    {
        return $this->hasMany(\App\Models\TouristImage::class, 'tourist_site_id');
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
}

