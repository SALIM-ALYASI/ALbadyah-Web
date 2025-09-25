<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TouristService extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'website_url',
        'image_url',
        'image_path',
        'governorate_id',
        'wilayat_id',
        'service_type_id',
    ];

    // نوع الخدمة (من جدول service_types)
    public function serviceType()
    {
        return $this->belongsTo(\App\Models\ServiceType::class, 'service_type_id');
    }

    // المحافظة (اختياري)
    public function governorate()
    {
        return $this->belongsTo(\App\Models\Governorate::class, 'governorate_id');
    }

    // الولاية (اختياري)
    public function wilayat()
    {
        return $this->belongsTo(\App\Models\Wilayat::class, 'wilayat_id');
    }
}

