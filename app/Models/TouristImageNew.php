<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TouristImageNew extends Model
{
    use HasFactory;

    protected $table = 'tourist_image_news';

    protected $fillable = [
        'tourist_site_id',
        'image_path',
        'image_url',
        'alt_text_ar',
        'alt_text_en',
        'caption_ar',
        'caption_en',
        'sort_order',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * كل صورة تنتمي لموقع سياحي
     */
    public function touristSite()
    {
        return $this->belongsTo(TouristSiteNew::class, 'tourist_site_id');
    }

    /**
     * الحصول على رابط الصورة الكامل
     */
    public function getImageUrlAttribute($value)
    {
        return \App\Helpers\ImageHelper::getImageUrl(
            $this->attributes['image_path'] ?? null,
            $this->attributes['image_url'] ?? null
        );
    }

    /**
     * الحصول على النص البديل حسب اللغة
     */
    public function getAltText($lang = 'ar')
    {
        return $lang === 'en' ? $this->alt_text_en : $this->alt_text_ar;
    }

    /**
     * الحصول على التسمية التوضيحية حسب اللغة
     */
    public function getCaption($lang = 'ar')
    {
        return $lang === 'en' ? $this->caption_en : $this->caption_ar;
    }

    /**
     * ترتيب الصور حسب sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    /**
     * فلترة الصور المميزة فقط
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}