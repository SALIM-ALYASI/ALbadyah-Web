<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GovernorateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'slug' => $this->slug,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'coordinates' => $this->when($this->latitude && $this->longitude, [
                'lat' => (float) $this->latitude,
                'lng' => (float) $this->longitude,
            ]),
            'stats' => [
                'wilayats_count' => $this->wilayats_count ?? $this->wilayats?->count() ?? 0,
                'tourist_sites_count' => $this->tourist_sites_count ?? $this->tourist_sites?->count() ?? 0,
                'tourist_services_count' => $this->tourist_services_count ?? $this->tourist_services?->count() ?? 0,
            ],
            'wilayats' => WilayatResource::collection($this->whenLoaded('wilayats')),
            'tourist_sites' => TouristSiteResource::collection($this->whenLoaded('tourist_sites')),
            'tourist_services' => TouristServiceResource::collection($this->whenLoaded('tourist_services')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
