<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TouristSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
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
}

