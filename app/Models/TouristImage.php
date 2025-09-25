<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TouristImage extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = ['tourist_site_id','image_url','image_path'];

    public function touristSite()
    {
        return $this->belongsTo(\App\Models\TouristSite::class, 'tourist_site_id');
    }
}
