<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
    ];

    // كل نوع خدمة يمتلك عدة خدمات سياحية
    public function touristServices()
    {
        return $this->hasMany(TouristService::class, 'service_type_id');
    }
}

