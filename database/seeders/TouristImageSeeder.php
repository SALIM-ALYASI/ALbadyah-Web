<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TouristImage;
use App\Models\TouristSite;

class TouristImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحصول على المواقع السياحية
        $touristSites = TouristSite::all();
        
        if ($touristSites->isEmpty()) {
            $this->command->warn('No tourist sites found. Please run TouristSiteSeeder first.');
            return;
        }

        $images = [
            // قلعة نزوى
            [
                'tourist_site_id' => $touristSites->where('name_ar', 'قلعة نزوى')->first()->id ?? 1,
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600&fit=crop'
            ],
            [
                'tourist_site_id' => $touristSites->where('name_ar', 'قلعة نزوى')->first()->id ?? 1,
                'image_url' => 'https://images.unsplash.com/photo-1587474260584-136574528ed5?w=800&h=600&fit=crop'
            ],
            [
                'tourist_site_id' => $touristSites->where('name_ar', 'قلعة نزوى')->first()->id ?? 1,
                'image_url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop'
            ],

            // قصر العلم
            [
                'tourist_site_id' => $touristSites->where('name_ar', 'قصر العلم')->first()->id ?? 2,
                'image_url' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&h=600&fit=crop'
            ],
            [
                'tourist_site_id' => $touristSites->where('name_ar', 'قصر العلم')->first()->id ?? 2,
                'image_url' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=800&h=600&fit=crop'
            ],

            // شاطئ القرم
            [
                'tourist_site_id' => $touristSites->where('name_ar', 'شاطئ القرم')->first()->id ?? 3,
                'image_url' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=600&fit=crop'
            ],
            [
                'tourist_site_id' => $touristSites->where('name_ar', 'شاطئ القرم')->first()->id ?? 3,
                'image_url' => 'https://images.unsplash.com/photo-1501594907352-04cda38ebc29?w=800&h=600&fit=crop'
            ],
            [
                'tourist_site_id' => $touristSites->where('name_ar', 'شاطئ القرم')->first()->id ?? 3,
                'image_url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop'
            ],

            // وادي شاب
            [
                'tourist_site_id' => $touristSites->where('name_ar', 'وادي شاب')->first()->id ?? 4,
                'image_url' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=800&h=600&fit=crop'
            ],
            [
                'tourist_site_id' => $touristSites->where('name_ar', 'وادي شاب')->first()->id ?? 4,
                'image_url' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=600&fit=crop'
            ],

            // سوق مطرح
            [
                'tourist_site_id' => $touristSites->where('name_ar', 'سوق مطرح')->first()->id ?? 5,
                'image_url' => 'https://images.unsplash.com/photo-1501594907352-04cda38ebc29?w=800&h=600&fit=crop'
            ],

            // متحف بيت الزبير
            [
                'tourist_site_id' => $touristSites->where('name_ar', 'متحف بيت الزبير')->first()->id ?? 6,
                'image_url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop'
            ],
            [
                'tourist_site_id' => $touristSites->where('name_ar', 'متحف بيت الزبير')->first()->id ?? 6,
                'image_url' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=800&h=600&fit=crop'
            ],

            // جبل شمس
            [
                'tourist_site_id' => $touristSites->where('name_ar', 'جبل شمس')->first()->id ?? 7,
                'image_url' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=600&fit=crop'
            ],
            [
                'tourist_site_id' => $touristSites->where('name_ar', 'جبل شمس')->first()->id ?? 7,
                'image_url' => 'https://images.unsplash.com/photo-1501594907352-04cda38ebc29?w=800&h=600&fit=crop'
            ],
            [
                'tourist_site_id' => $touristSites->where('name_ar', 'جبل شمس')->first()->id ?? 7,
                'image_url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop'
            ],

            // شاطئ المغسيل
            [
                'tourist_site_id' => $touristSites->where('name_ar', 'شاطئ المغسيل')->first()->id ?? 8,
                'image_url' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=800&h=600&fit=crop'
            ],
            [
                'tourist_site_id' => $touristSites->where('name_ar', 'شاطئ المغسيل')->first()->id ?? 8,
                'image_url' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=600&fit=crop'
            ],

            // قلعة صحار
            [
                'tourist_site_id' => $touristSites->where('name_ar', 'قلعة صحار')->first()->id ?? 9,
                'image_url' => 'https://images.unsplash.com/photo-1501594907352-04cda38ebc29?w=800&h=600&fit=crop'
            ],

            // وادي بني خالد
            [
                'tourist_site_id' => $touristSites->where('name_ar', 'وادي بني خالد')->first()->id ?? 10,
                'image_url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop'
            ],
            [
                'tourist_site_id' => $touristSites->where('name_ar', 'وادي بني خالد')->first()->id ?? 10,
                'image_url' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=800&h=600&fit=crop'
            ]
        ];

        foreach ($images as $image) {
            TouristImage::create($image);
        }
    }
}