<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceType;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceTypes = [
            [
                'name_ar' => 'الفنادق',
                'name_en' => 'Hotels'
            ],
            [
                'name_ar' => 'المطاعم',
                'name_en' => 'Restaurants'
            ],
            [
                'name_ar' => 'النقل',
                'name_en' => 'Transportation'
            ],
            [
                'name_ar' => 'الترفيه',
                'name_en' => 'Entertainment'
            ],
            [
                'name_ar' => 'الخدمات السياحية',
                'name_en' => 'Tourist Services'
            ],
            [
                'name_ar' => 'التسوق',
                'name_en' => 'Shopping'
            ],
            [
                'name_ar' => 'الرياضة',
                'name_en' => 'Sports'
            ],
            [
                'name_ar' => 'الرعاية الصحية',
                'name_en' => 'Healthcare'
            ],
            [
                'name_ar' => 'الخدمات المصرفية',
                'name_en' => 'Banking'
            ],
            [
                'name_ar' => 'التعليم',
                'name_en' => 'Education'
            ]
        ];

        foreach ($serviceTypes as $serviceType) {
            ServiceType::create($serviceType);
        }
    }
}