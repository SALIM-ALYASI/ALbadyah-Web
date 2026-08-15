<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'base_url',
        'trust_level',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'trust_level' => 'integer',
    ];

    public function touristSites()
    {
        return $this->hasMany(TouristSite::class);
    }

    public function touristServices()
    {
        return $this->hasMany(TouristService::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
