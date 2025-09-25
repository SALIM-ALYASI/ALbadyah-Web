<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Governorate extends Model
{
    use HasFactory;

    // اسم الجدول (Laravel يتعرف عليه تلقائيًا لأنه جمع اسم الموديل)
    protected $table = 'governorates';

    // الحقول المسموح تعبئتها
    protected $fillable = [
        'name_ar',
        'name_en',
        'website_url',
        'image_url',
        'image_path',
    ];

    public function wilayats()
    {
        return $this->hasMany(\App\Models\Wilayat::class, 'governorate_id');
    }
    public function touristSites()
    {
        return $this->hasMany(\App\Models\TouristSite::class, 'governorate_id');
    }
    public function touristServices()
    {
        return $this->hasMany(\App\Models\TouristService::class, 'governorate_id');
    }
}
