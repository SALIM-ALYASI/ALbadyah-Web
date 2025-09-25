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
}
