<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TouristSiteResource extends JsonResource
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
            'address_ar' => $this->address_ar,
            'address_en' => $this->address_en,
            'phone' => $this->phone,
            'email' => $this->email,
            'website' => $this->website,
            'opening_hours' => $this->opening_hours,
            'entry_fee' => $this->entry_fee,
            'governorate' => $this->whenLoaded('governorate', function () {
                return [
                    'id' => $this->governorate->id,
                    'name_ar' => $this->governorate->name_ar,
                    'name_en' => $this->governorate->name_en,
                ];
            }),
            'wilayat' => $this->whenLoaded('wilayat', function () {
                return [
                    'id' => $this->wilayat->id,
                    'name_ar' => $this->wilayat->name_ar,
                    'name_en' => $this->wilayat->name_en,
                ];
            }),
            'images' => $this->whenLoaded('images', function () {
                return $this->images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'url' => $image->image_url,
                        'alt' => $image->alt_text ?? $this->name_ar,
                        'created_at' => $image->created_at?->format('Y-m-d H:i:s'),
                    ];
                });
            }),
            'main_image' => $this->when($this->images && $this->images->count() > 0, function () {
                $firstImage = $this->images->first();
                return [
                    'url' => $firstImage->image_url,
                    'alt' => $firstImage->alt_text ?? $this->name_ar,
                ];
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
