<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TouristImage extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'tourist_site_id',
        'image_url',
        'image_path',
        'alt_text_ar',
        'alt_text_en',
        'sort_order',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function touristSite()
    {
        return $this->belongsTo(TouristSite::class, 'tourist_site_id');
    }

    /**
     * Get the image URL accessor
     */
    public function getImageUrlAttribute()
    {
        return \App\Helpers\ImageHelper::getImageUrl(
            $this->attributes['image_path'] ?? null,
            $this->attributes['image_url'] ?? null
        );
    }

    /**
     * Check if image exists
     */
    public function getHasImageAttribute()
    {
        return \App\Helpers\ImageHelper::hasImage(
            $this->attributes['image_path'] ?? null,
            $this->attributes['image_url'] ?? null
        );
    }

    /**
     * Get image info
     */
    public function getImageInfoAttribute()
    {
        return \App\Helpers\ImageHelper::getImageInfo(
            $this->attributes['image_path'] ?? null,
            $this->attributes['image_url'] ?? null
        );
    }

    /**
     * Get alt text by language
     */
    public function getAltText($lang = 'ar')
    {
        return $lang === 'en' ? $this->alt_text_en : $this->alt_text_ar;
    }

    /**
     * Scope for ordered images
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    /**
     * Scope for featured images
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
