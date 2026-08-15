<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TouristSiteCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name_ar', 'name_en', 'slug'];

    public function touristSites()
    {
        return $this->hasMany(TouristSite::class);
    }
}
