<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TouristService;
use App\Models\Governorate;
use App\Models\Wilayat;
use App\Models\ServiceType;

class TouristServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحصول على المحافظات والولايات وأنواع الخدمات
        $governorates = Governorate::all();
        $wilayats = Wilayat::all();
        $serviceTypes = ServiceType::all();
        
        if ($governorates->isEmpty() || $wilayats->isEmpty() || $serviceTypes->isEmpty()) {
            $this->command->warn('No governorates, wilayats or service types found. Please run their seeders first.');
            return;
        }

        $touristServices = [
            // فنادق
            [
                'name_ar' => 'فندق جراند حياة مسقط',
                'name_en' => 'Grand Hyatt Muscat',
                'website_url' => 'https://www.grandhyattmuscat.com',
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'مسقط')->first()->id ?? 1,
                'wilayat_id' => $wilayats->where('name_ar', 'مسقط')->first()->id ?? 1,
                'service_type_id' => $serviceTypes->where('name_ar', 'الفنادق')->first()->id ?? 1
            ],
            [
                'name_ar' => 'فندق شيراتون مسقط',
                'name_en' => 'Sheraton Muscat Hotel',
                'website_url' => 'https://www.sheratonmuscat.com',
                'image_url' => 'https://images.unsplash.com/photo-1587474260584-136574528ed5?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'مسقط')->first()->id ?? 1,
                'wilayat_id' => $wilayats->where('name_ar', 'مطرح')->first()->id ?? 2,
                'service_type_id' => $serviceTypes->where('name_ar', 'الفنادق')->first()->id ?? 1
            ],
            [
                'name_ar' => 'فندق قصر البستان',
                'name_en' => 'Al Bustan Palace Hotel',
                'website_url' => 'https://www.albustanpalace.com',
                'image_url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'مسقط')->first()->id ?? 1,
                'wilayat_id' => $wilayats->where('name_ar', 'مسقط')->first()->id ?? 1,
                'service_type_id' => $serviceTypes->where('name_ar', 'الفنادق')->first()->id ?? 1
            ],

            // مطاعم
            [
                'name_ar' => 'مطعم بيت اللبان',
                'name_en' => 'Bait Al Luban Restaurant',
                'website_url' => 'https://www.baitalluban.com',
                'image_url' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'مسقط')->first()->id ?? 1,
                'wilayat_id' => $wilayats->where('name_ar', 'مطرح')->first()->id ?? 2,
                'service_type_id' => $serviceTypes->where('name_ar', 'المطاعم')->first()->id ?? 2
            ],
            [
                'name_ar' => 'مطعم الكوثر',
                'name_en' => 'Al Kawthar Restaurant',
                'website_url' => 'https://www.alkawthar.om',
                'image_url' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'مسقط')->first()->id ?? 1,
                'wilayat_id' => $wilayats->where('name_ar', 'مسقط')->first()->id ?? 1,
                'service_type_id' => $serviceTypes->where('name_ar', 'المطاعم')->first()->id ?? 2
            ],

            // نقل
            [
                'name_ar' => 'شركة النقل العمانية',
                'name_en' => 'Oman Transport Company',
                'website_url' => 'https://www.omantransport.com',
                'image_url' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'مسقط')->first()->id ?? 1,
                'wilayat_id' => $wilayats->where('name_ar', 'مسقط')->first()->id ?? 1,
                'service_type_id' => $serviceTypes->where('name_ar', 'النقل')->first()->id ?? 3
            ],
            [
                'name_ar' => 'تاكسي مسقط',
                'name_en' => 'Muscat Taxi',
                'website_url' => 'https://www.muscattaxi.om',
                'image_url' => 'https://images.unsplash.com/photo-1501594907352-04cda38ebc29?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'مسقط')->first()->id ?? 1,
                'wilayat_id' => $wilayats->where('name_ar', 'مطرح')->first()->id ?? 2,
                'service_type_id' => $serviceTypes->where('name_ar', 'النقل')->first()->id ?? 3
            ],

            // ترفيه
            [
                'name_ar' => 'مدينة الألعاب المائية',
                'name_en' => 'Water Park City',
                'website_url' => 'https://www.waterparkmuscat.com',
                'image_url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'مسقط')->first()->id ?? 1,
                'wilayat_id' => $wilayats->where('name_ar', 'السيب')->first()->id ?? 3,
                'service_type_id' => $serviceTypes->where('name_ar', 'الترفيه')->first()->id ?? 4
            ],
            [
                'name_ar' => 'سينما مسقط',
                'name_en' => 'Muscat Cinema',
                'website_url' => 'https://www.muscatsinema.com',
                'image_url' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'مسقط')->first()->id ?? 1,
                'wilayat_id' => $wilayats->where('name_ar', 'مسقط')->first()->id ?? 1,
                'service_type_id' => $serviceTypes->where('name_ar', 'الترفيه')->first()->id ?? 4
            ],

            // خدمات سياحية
            [
                'name_ar' => 'وكالة السفر العمانية',
                'name_en' => 'Oman Travel Agency',
                'website_url' => 'https://www.omantravel.om',
                'image_url' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'مسقط')->first()->id ?? 1,
                'wilayat_id' => $wilayats->where('name_ar', 'مسقط')->first()->id ?? 1,
                'service_type_id' => $serviceTypes->where('name_ar', 'الخدمات السياحية')->first()->id ?? 5
            ],
            [
                'name_ar' => 'مرشد سياحي نزوى',
                'name_en' => 'Nizwa Tour Guide',
                'website_url' => 'https://www.nizwatours.com',
                'image_url' => 'https://images.unsplash.com/photo-1501594907352-04cda38ebc29?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'الداخلية')->first()->id ?? 2,
                'wilayat_id' => $wilayats->where('name_ar', 'نزوى')->first()->id ?? 4,
                'service_type_id' => $serviceTypes->where('name_ar', 'الخدمات السياحية')->first()->id ?? 5
            ],

            // تسوق
            [
                'name_ar' => 'مركز العاصمة التجاري',
                'name_en' => 'Capital Mall',
                'website_url' => 'https://www.capitalmall.om',
                'image_url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'مسقط')->first()->id ?? 1,
                'wilayat_id' => $wilayats->where('name_ar', 'السيب')->first()->id ?? 3,
                'service_type_id' => $serviceTypes->where('name_ar', 'التسوق')->first()->id ?? 6
            ],
            [
                'name_ar' => 'سوق الجمعية',
                'name_en' => 'Al Jamaa Market',
                'website_url' => 'https://www.aljamaamarket.om',
                'image_url' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'مسقط')->first()->id ?? 1,
                'wilayat_id' => $wilayats->where('name_ar', 'مسقط')->first()->id ?? 1,
                'service_type_id' => $serviceTypes->where('name_ar', 'التسوق')->first()->id ?? 6
            ],

            // رياضة
            [
                'name_ar' => 'نادي مسقط الرياضي',
                'name_en' => 'Muscat Sports Club',
                'website_url' => 'https://www.muscatsports.com',
                'image_url' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'مسقط')->first()->id ?? 1,
                'wilayat_id' => $wilayats->where('name_ar', 'السيب')->first()->id ?? 3,
                'service_type_id' => $serviceTypes->where('name_ar', 'الرياضة')->first()->id ?? 7
            ],

            // رعاية صحية
            [
                'name_ar' => 'مستشفى السلطان قابوس',
                'name_en' => 'Sultan Qaboos Hospital',
                'website_url' => 'https://www.sqh.om',
                'image_url' => 'https://images.unsplash.com/photo-1501594907352-04cda38ebc29?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'مسقط')->first()->id ?? 1,
                'wilayat_id' => $wilayats->where('name_ar', 'مسقط')->first()->id ?? 1,
                'service_type_id' => $serviceTypes->where('name_ar', 'الرعاية الصحية')->first()->id ?? 8
            ],

            // خدمات مصرفية
            [
                'name_ar' => 'بنك مسقط',
                'name_en' => 'Bank Muscat',
                'website_url' => 'https://www.bankmuscat.com',
                'image_url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'مسقط')->first()->id ?? 1,
                'wilayat_id' => $wilayats->where('name_ar', 'مسقط')->first()->id ?? 1,
                'service_type_id' => $serviceTypes->where('name_ar', 'الخدمات المصرفية')->first()->id ?? 9
            ],

            // تعليم
            [
                'name_ar' => 'جامعة السلطان قابوس',
                'name_en' => 'Sultan Qaboos University',
                'website_url' => 'https://www.squ.edu.om',
                'image_url' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=800&h=600&fit=crop',
                'governorate_id' => $governorates->where('name_ar', 'مسقط')->first()->id ?? 1,
                'wilayat_id' => $wilayats->where('name_ar', 'السيب')->first()->id ?? 3,
                'service_type_id' => $serviceTypes->where('name_ar', 'التعليم')->first()->id ?? 10
            ]
        ];

        foreach ($touristServices as $touristService) {
            TouristService::create($touristService);
        }
    }
}