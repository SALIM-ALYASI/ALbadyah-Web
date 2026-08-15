<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TouristServiceImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'tourist_service_id',
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

    public function touristService()
    {
        return $this->belongsTo(TouristService::class);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getImageUrlAttribute($value)
    {
        return \App\Helpers\ImageHelper::getImageUrl($this->attributes['image_path'] ?? null, $value);
    }
}
