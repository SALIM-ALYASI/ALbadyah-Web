<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * مسودة مرشّح من بوت البادية (تلجرام) - مصادر متعددة + درجة ثقة، بانتظار
 * قرار الأدمن. منفصل تمامًا عن TouristSite/TouristService، ولا يُنشر
 * تلقائيًا للموقع العام.
 */
class MapCandidate extends Model
{
    protected $fillable = [
        'category',
        'osm_type', 'osm_id', 'wikidata_id',
        'name_ar', 'name_en', 'subtype',
        'latitude', 'longitude',
        'phone', 'website', 'opening_hours', 'address_ar',
        'wilayat_id', 'governorate_id',
        'description_ar', 'description_en',
        'image_url', 'image_urls', 'image_source', 'image_license', 'image_is_placeholder',
        'tourist_site_category_id', 'service_type_id',
        'sources', 'field_confidence', 'overall_confidence', 'missing_fields',
        'status', 'rejected_reason', 'telegram_admin_id',
        'published_table', 'published_id', 'published_at',
    ];

    protected $casts = [
        'sources' => 'array',
        'field_confidence' => 'array',
        'missing_fields' => 'array',
        'image_urls' => 'array',
        'image_is_placeholder' => 'boolean',
        'overall_confidence' => 'float',
        'latitude' => 'float',
        'longitude' => 'float',
        'published_at' => 'datetime',
    ];

    public function wilayat()
    {
        return $this->belongsTo(Wilayat::class);
    }

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }
}
