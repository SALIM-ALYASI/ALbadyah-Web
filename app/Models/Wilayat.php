<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
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
        return $this->belongsTo(\App\Models\Governorate::class, 'governorate_id');
    }
    public function touristSites()
    {
        return $this->hasMany(\App\Models\TouristSite::class, 'wilayat_id');
    }
    public function touristServices()
    {
        return $this->hasMany(\App\Models\TouristService::class, 'wilayat_id');
    }

    /**
     * الحصول على رابط الصورة
     */
    public function getImageUrlAttribute()
    {
        return getImageUrl($this->attributes['image_path'] ?? null, $this->attributes['image_url'] ?? null);
    }

    /**
     * التحقق من وجود الصورة
     */
    public function getHasImageAttribute()
    {
        return hasImage($this->attributes['image_path'] ?? null, $this->attributes['image_url'] ?? null);
    }

    /**
     * الحصول على معلومات الصورة
     */
    public function getImageInfoAttribute()
    {
        return getImageInfo($this->attributes['image_path'] ?? null, $this->attributes['image_url'] ?? null);
    }
}
