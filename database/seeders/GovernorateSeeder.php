<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Governorate;

class GovernorateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $governorates = [
            [
                'name_ar' => 'مسقط',
                'name_en' => 'Muscat',
                'website_url' => 'https://www.muscat.gov.om',
                'image_url' => 'https://images.unsplash.com/photo-1587474260584-136574528ed5?w=800&h=600&fit=crop'
            ],
            [
                'name_ar' => 'الداخلية',
                'name_en' => 'Ad Dakhiliyah',
                'website_url' => 'https://www.dakhiliyah.gov.om',
                'image_url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop'
            ],
            [
                'name_ar' => 'ظفار',
                'name_en' => 'Dhofar',
                'website_url' => 'https://www.dhofar.gov.om',
                'image_url' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&h=600&fit=crop'
            ],
            [
                'name_ar' => 'الباطنة',
                'name_en' => 'Al Batinah',
                'website_url' => 'https://www.batinah.gov.om',
                'image_url' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=800&h=600&fit=crop'
            ],
            [
                'name_ar' => 'الشرقية',
                'name_en' => 'Ash Sharqiyah',
                'website_url' => 'https://www.sharqiyah.gov.om',
                'image_url' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=600&fit=crop'
            ],
            [
                'name_ar' => 'الوسطى',
                'name_en' => 'Al Wusta',
                'website_url' => 'https://www.wusta.gov.om',
                'image_url' => 'https://images.unsplash.com/photo-1501594907352-04cda38ebc29?w=800&h=600&fit=crop'
            ],
            [
                'name_ar' => 'البريمي',
                'name_en' => 'Al Buraymi',
                'website_url' => 'https://www.buraymi.gov.om',
                'image_url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop'
            ],
            [
                'name_ar' => 'مسندم',
                'name_en' => 'Musandam',
                'website_url' => 'https://www.musandam.gov.om',
                'image_url' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=800&h=600&fit=crop'
            ]
        ];

        foreach ($governorates as $governorate) {
            Governorate::create($governorate);
        }
    }
}