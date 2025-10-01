<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TouristImage extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = ['tourist_site_id','image_url','image_path'];

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
}
