<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TouristSite;
use App\Models\Governorate;
use App\Models\Wilayat;

class TouristSiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحصول على المحافظات والولايات
        $governorates = Governorate::all();
        $wilayats = Wilayat::all();
        
        if ($governorates->isEmpty() || $wilayats->isEmpty()) {
            $this->command->warn('No governorates or wilayats found. Please run GovernorateSeeder and WilayatSeeder first.');
            return;
        }

        $touristSites = [
            [
                'name_ar' => 'قلعة نزوى',
                'name_en' => 'Nizwa Fort',
                'description_ar' => 'قلعة تاريخية عريقة في مدينة نزوى، تعتبر من أهم المعالم السياحية في سلطنة عمان. بُنيت في القرن السابع عشر وتتميز بتصميمها المعماري الفريد.',
                'description_en' => 'A historic fortress in Nizwa city, considered one of the most important tourist attractions in Oman. Built in the 17th century and features unique architectural design.',
                'location' => 'نزوى، الداخلية',
                'website_url' => 'https://www.nizwafort.com',
                'governorate_id' => $governorates->where('name_ar', 'الداخلية')->first()->id ?? 2,
                'wilayat_id' => $wilayats->where('name_ar', 'نزوى')->first()->id ?? 4
            ],
            [
                'name_ar' => 'قصر العلم',
                'name_en' => 'Al Alam Palace',
                'description_ar' => 'قصر سلطاني فاخر يقع في قلب مسقط، وهو المقر الرسمي للسلطان. يتميز بواجهته الزرقاء والذهبية الجميلة.',
                'description_en' => 'A luxurious royal palace located in the heart of Muscat, the official residence of the Sultan. Features beautiful blue and gold facade.',
                'location' => 'مسقط، مسقط',
                'website_url' => 'https://www.alam-palace.om',
                'governorate_id' => $governorates->where('name_ar', 'مسقط')->first()->id ?? 1,
                'wilayat_id' => $wilayats->where('name_ar', 'مسقط')->first()->id ?? 1
            ],
            [
                'name_ar' => 'شاطئ القرم',
                'name_en' => 'Qurum Beach',
                'description_ar' => 'أحد أجمل الشواطئ في مسقط، يتميز برماله البيضاء الناعمة ومياهه الصافية. مكان مثالي للاستجمام والرياضات المائية.',
                'description_en' => 'One of the most beautiful beaches in Muscat, featuring soft white sand and crystal clear waters. Perfect place for relaxation and water sports.',
                'location' => 'القرم، مسقط',
                'website_url' => 'https://www.qurumbeach.om',
                'governorate_id' => $governorates->where('name_ar', 'مسقط')->first()->id ?? 1,
                'wilayat_id' => $wilayats->where('name_ar', 'مسقط')->first()->id ?? 1
            ],
            [
                'name_ar' => 'وادي شاب',
                'name_en' => 'Wadi Shab',
                'description_ar' => 'وادي خلاب يقع في محافظة الشرقية، يتميز ببرك المياه الطبيعية والشلالات الجميلة. وجهة مثالية لمحبي الطبيعة والمغامرة.',
                'description_en' => 'A stunning wadi located in Ash Sharqiyah Governorate, featuring natural water pools and beautiful waterfalls. Perfect destination for nature and adventure lovers.',
                'location' => 'الشرقية',
                'website_url' => 'https://www.wadishab.om',
                'governorate_id' => $governorates->where('name_ar', 'الشرقية')->first()->id ?? 5,
                'wilayat_id' => null
            ],
            [
                'name_ar' => 'سوق مطرح',
                'name_en' => 'Muttrah Souq',
                'description_ar' => 'سوق تقليدي عريق في مطرح، يعتبر من أقدم الأسواق في الخليج. يبيع البخور والعطور والتوابل والتحف التقليدية.',
                'description_en' => 'A traditional historic souq in Muttrah, considered one of the oldest markets in the Gulf. Sells incense, perfumes, spices and traditional handicrafts.',
                'location' => 'مطرح، مسقط',
                'website_url' => 'https://www.mutrahsouq.om',
                'governorate_id' => $governorates->where('name_ar', 'مسقط')->first()->id ?? 1,
                'wilayat_id' => $wilayats->where('name_ar', 'مطرح')->first()->id ?? 2
            ],
            [
                'name_ar' => 'متحف بيت الزبير',
                'name_en' => 'Bait Al Zubair Museum',
                'description_ar' => 'متحف خاص يعرض تاريخ وثقافة عمان، يحتوي على مجموعة رائعة من التحف والوثائق التاريخية والأسلحة التقليدية.',
                'description_en' => 'A private museum displaying Oman\'s history and culture, featuring a wonderful collection of artifacts, historical documents and traditional weapons.',
                'location' => 'مسقط، مسقط',
                'website_url' => 'https://www.baitzubair.com',
                'governorate_id' => $governorates->where('name_ar', 'مسقط')->first()->id ?? 1,
                'wilayat_id' => $wilayats->where('name_ar', 'مسقط')->first()->id ?? 1
            ],
            [
                'name_ar' => 'جبل شمس',
                'name_en' => 'Jebel Shams',
                'description_ar' => 'أعلى قمة جبلية في سلطنة عمان، يتميز بإطلالات خلابة على الوديان العميقة. وجهة مثالية لمحبي التسلق والمشي.',
                'description_en' => 'The highest mountain peak in Oman, featuring stunning views of deep valleys. Perfect destination for climbing and hiking enthusiasts.',
                'location' => 'الحمراء، الداخلية',
                'website_url' => 'https://www.jebelshams.om',
                'governorate_id' => $governorates->where('name_ar', 'الداخلية')->first()->id ?? 2,
                'wilayat_id' => $wilayats->where('name_ar', 'الحمراء')->first()->id ?? 6
            ],
            [
                'name_ar' => 'شاطئ المغسيل',
                'name_en' => 'Mughsail Beach',
                'description_ar' => 'شاطئ رائع في صلالة يتميز بنوافير المياه الطبيعية التي تندفع من الصخور. مشهد طبيعي فريد يجذب السياح.',
                'description_en' => 'A wonderful beach in Salalah featuring natural water fountains that shoot out from the rocks. Unique natural scene that attracts tourists.',
                'location' => 'صلالة، ظفار',
                'website_url' => 'https://www.mughsail.om',
                'governorate_id' => $governorates->where('name_ar', 'ظفار')->first()->id ?? 3,
                'wilayat_id' => $wilayats->where('name_ar', 'صلالة')->first()->id ?? 7
            ],
            [
                'name_ar' => 'قلعة صحار',
                'name_en' => 'Sohar Fort',
                'description_ar' => 'قلعة تاريخية في مدينة صحار، تعود إلى القرن الثالث عشر. تتميز بتصميمها الدفاعي الرائع وتاريخها العريق.',
                'description_en' => 'A historic fortress in Sohar city, dating back to the 13th century. Features magnificent defensive design and rich history.',
                'location' => 'صحار، الباطنة',
                'website_url' => 'https://www.soharfort.om',
                'governorate_id' => $governorates->where('name_ar', 'الباطنة')->first()->id ?? 4,
                'wilayat_id' => $wilayats->where('name_ar', 'صحار')->first()->id ?? 10
            ],
            [
                'name_ar' => 'وادي بني خالد',
                'name_en' => 'Wadi Bani Khalid',
                'description_ar' => 'وادي جميل في محافظة الشرقية، يتميز ببرك المياه الكريستالية والشلالات المتدفقة. وجهة مثالية للسباحة والاستجمام.',
                'description_en' => 'A beautiful wadi in Ash Sharqiyah Governorate, featuring crystal water pools and flowing waterfalls. Perfect destination for swimming and relaxation.',
                'location' => 'الشرقية',
                'website_url' => 'https://www.wadibanikhalid.om',
                'governorate_id' => $governorates->where('name_ar', 'الشرقية')->first()->id ?? 5,
                'wilayat_id' => null
            ]
        ];

        foreach ($touristSites as $touristSite) {
            TouristSite::create($touristSite);
        }
    }
}