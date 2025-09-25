<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wilayat;
use App\Models\Governorate;

class WilayatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحصول على المحافظات
        $governorates = Governorate::all();
        
        if ($governorates->isEmpty()) {
            $this->command->warn('No governorates found. Please run GovernorateSeeder first.');
            return;
        }

        $wilayats = [
            // مسقط
            [
                'name_ar' => 'مسقط',
                'name_en' => 'Muscat',
                'website_url' => 'https://www.muscat.gov.om',
                'image_url' => 'https://images.unsplash.com/photo-1587474260584-136574528ed5?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'مسقط')->first()->id ?? 1
            ],
            [
                'name_ar' => 'مطرح',
                'name_en' => 'Muttrah',
                'website_url' => 'https://www.muttrah.gov.om',
                'image_url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'مسقط')->first()->id ?? 1
            ],
            [
                'name_ar' => 'السيب',
                'name_en' => 'Seeb',
                'website_url' => 'https://www.seeb.gov.om',
                'image_url' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'مسقط')->first()->id ?? 1
            ],

            // الداخلية
            [
                'name_ar' => 'نزوى',
                'name_en' => 'Nizwa',
                'website_url' => 'https://www.nizwa.gov.om',
                'image_url' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'الداخلية')->first()->id ?? 2
            ],
            [
                'name_ar' => 'بهلا',
                'name_en' => 'Bahla',
                'website_url' => 'https://www.bahla.gov.om',
                'image_url' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'الداخلية')->first()->id ?? 2
            ],
            [
                'name_ar' => 'الحمراء',
                'name_en' => 'Al Hamra',
                'website_url' => 'https://www.alhamra.gov.om',
                'image_url' => 'https://images.unsplash.com/photo-1501594907352-04cda38ebc29?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'الداخلية')->first()->id ?? 2
            ],

            // ظفار
            [
                'name_ar' => 'صلالة',
                'name_en' => 'Salalah',
                'website_url' => 'https://www.salalah.gov.om',
                'image_url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'ظفار')->first()->id ?? 3
            ],
            [
                'name_ar' => 'طاقة',
                'name_en' => 'Taqah',
                'website_url' => 'https://www.taqah.gov.om',
                'image_url' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'ظفار')->first()->id ?? 3
            ],
            [
                'name_ar' => 'مرباط',
                'name_en' => 'Mirbat',
                'website_url' => 'https://www.mirbat.gov.om',
                'image_url' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'ظفار')->first()->id ?? 3
            ],

            // الباطنة
            [
                'name_ar' => 'صحار',
                'name_en' => 'Sohar',
                'website_url' => 'https://www.sohar.gov.om',
                'image_url' => 'https://images.unsplash.com/photo-1501594907352-04cda38ebc29?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'الباطنة')->first()->id ?? 4
            ],
            [
                'name_ar' => 'الرستاق',
                'name_en' => 'Rustaq',
                'website_url' => 'https://www.rustaq.gov.om',
                'image_url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'الباطنة')->first()->id ?? 4
            ],
            [
                'name_ar' => 'شناص',
                'name_en' => 'Shinas',
                'website_url' => 'https://www.shinas.gov.om',
                'image_url' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'الباطنة')->first()->id ?? 4
            ]
        ];

        foreach ($wilayats as $wilayat) {
            Wilayat::create($wilayat);
        }
    }
}