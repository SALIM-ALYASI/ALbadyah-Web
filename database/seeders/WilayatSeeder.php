<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wilayat;
use App\Models\Governorate;

class WilayatSeeder extends Seeder
{
    /**
     * الـ63 ولاية الرسمية لسلطنة عُمان، موزّعة على محافظاتها الإحدى عشرة.
     * idempotent: يطابق بالاسم العربي فقط، فيحدّث الموجود بدل تكراره
     * (مهم للولايات الست الموجودة أصلاً تحت محافظة مسقط بقاعدة بيانات الإنتاج).
     */
    public function run(): void
    {
        $governorates = Governorate::all()->keyBy('name_ar');

        if ($governorates->isEmpty()) {
            $this->command->warn('No governorates found. Please run GovernorateSeeder first.');
            return;
        }

        $wilayatsByGovernorate = [
            'مسقط' => [
                ['name_ar' => 'مسقط', 'name_en' => 'Muscat'],
                ['name_ar' => 'مطرح', 'name_en' => 'Muttrah'],
                ['name_ar' => 'العامرات', 'name_en' => 'Al Amerat'],
                ['name_ar' => 'بوشر', 'name_en' => 'Bawshar'],
                ['name_ar' => 'السيب', 'name_en' => 'Seeb'],
                ['name_ar' => 'قريات', 'name_en' => 'Quriyat'],
            ],
            'الداخلية' => [
                ['name_ar' => 'نزوى', 'name_en' => 'Nizwa'],
                ['name_ar' => 'بهلا', 'name_en' => 'Bahla'],
                ['name_ar' => 'منح', 'name_en' => 'Manah'],
                ['name_ar' => 'الحمراء', 'name_en' => 'Al Hamra'],
                ['name_ar' => 'أدم', 'name_en' => 'Adam'],
                ['name_ar' => 'إزكي', 'name_en' => 'Izki'],
                ['name_ar' => 'سمائل', 'name_en' => 'Samail'],
                ['name_ar' => 'بدبد', 'name_en' => 'Bidbid'],
                ['name_ar' => 'الجبل الأخضر', 'name_en' => 'Jabal Akhdar'],
            ],
            'شمال الباطنة' => [
                ['name_ar' => 'صحار', 'name_en' => 'Sohar'],
                ['name_ar' => 'شناص', 'name_en' => 'Shinas'],
                ['name_ar' => 'لوى', 'name_en' => 'Liwa'],
                ['name_ar' => 'صحم', 'name_en' => 'Saham'],
                ['name_ar' => 'الخابورة', 'name_en' => 'Khaburah'],
                ['name_ar' => 'السويق', 'name_en' => 'Suwaiq'],
            ],
            'جنوب الباطنة' => [
                ['name_ar' => 'الرستاق', 'name_en' => 'Rustaq'],
                ['name_ar' => 'العوابي', 'name_en' => 'Awabi'],
                ['name_ar' => 'نخل', 'name_en' => 'Nakhal'],
                ['name_ar' => 'وادي المعاول', 'name_en' => 'Wadi Al Maawil'],
                ['name_ar' => 'بركاء', 'name_en' => 'Barka'],
                ['name_ar' => 'المصنعة', 'name_en' => 'Musanaah'],
            ],
            'الوسطى' => [
                ['name_ar' => 'هيما', 'name_en' => 'Haima'],
                ['name_ar' => 'محوت', 'name_en' => 'Mahoot'],
                ['name_ar' => 'الدقم', 'name_en' => 'Duqm'],
                ['name_ar' => 'الجازر', 'name_en' => 'Al Jazir'],
            ],
            'شمال الشرقية' => [
                ['name_ar' => 'إبراء', 'name_en' => 'Ibra'],
                ['name_ar' => 'المضيبي', 'name_en' => 'Mudhaibi'],
                ['name_ar' => 'بدية', 'name_en' => 'Bidiyah'],
                ['name_ar' => 'القابل', 'name_en' => 'Al Qabil'],
                ['name_ar' => 'وادي بني خالد', 'name_en' => 'Wadi Bani Khalid'],
                ['name_ar' => 'دماء الطائيين', 'name_en' => 'Dama Wa Al Taiyin'],
                ['name_ar' => 'سناو', 'name_en' => 'Sinaw'],
            ],
            'جنوب الشرقية' => [
                ['name_ar' => 'صور', 'name_en' => 'Sur'],
                ['name_ar' => 'الكامل والوافي', 'name_en' => 'Al Kamil Wal Wafi'],
                ['name_ar' => 'جعلان بني بوحسن', 'name_en' => 'Jalan Bani Bu Hassan'],
                ['name_ar' => 'جعلان بني بوعلي', 'name_en' => 'Jalan Bani Bu Ali'],
                ['name_ar' => 'مصيرة', 'name_en' => 'Masirah'],
            ],
            'الظاهرة' => [
                ['name_ar' => 'عبري', 'name_en' => 'Ibri'],
                ['name_ar' => 'ينقل', 'name_en' => 'Yanqul'],
                ['name_ar' => 'ضنك', 'name_en' => 'Dank'],
            ],
            'مسندم' => [
                ['name_ar' => 'خصب', 'name_en' => 'Khasab'],
                ['name_ar' => 'دبا', 'name_en' => 'Dibba'],
                ['name_ar' => 'بخا', 'name_en' => 'Bukha'],
                ['name_ar' => 'مدحاء', 'name_en' => 'Madha'],
            ],
            'ظفار' => [
                ['name_ar' => 'صلالة', 'name_en' => 'Salalah'],
                ['name_ar' => 'طاقة', 'name_en' => 'Taqah'],
                ['name_ar' => 'مرباط', 'name_en' => 'Mirbat'],
                ['name_ar' => 'رخيوت', 'name_en' => 'Rakhyut'],
                ['name_ar' => 'ثمريت', 'name_en' => 'Thumrait'],
                ['name_ar' => 'ضلكوت', 'name_en' => 'Dhalkut'],
                ['name_ar' => 'المزيونة', 'name_en' => 'Al Mazyona'],
                ['name_ar' => 'مقشن', 'name_en' => 'Muqshin'],
                ['name_ar' => 'شليم وجزر الحلانيات', 'name_en' => 'Shalim and the Hallaniyat Islands'],
                ['name_ar' => 'سدح', 'name_en' => 'Sadah'],
            ],
            'البريمي' => [
                ['name_ar' => 'البريمي', 'name_en' => 'Al Buraimi'],
                ['name_ar' => 'محضة', 'name_en' => 'Mahdah'],
                ['name_ar' => 'السنينة', 'name_en' => 'As-Sunaynah'],
            ],
        ];

        foreach ($wilayatsByGovernorate as $governorateNameAr => $wilayats) {
            $governorate = $governorates->get($governorateNameAr);
            if (!$governorate) {
                $this->command->warn("Governorate not found: {$governorateNameAr} — skipping its wilayats.");
                continue;
            }

            foreach ($wilayats as $wilayat) {
                Wilayat::updateOrCreate(
                    ['name_ar' => $wilayat['name_ar']],
                    ['name_en' => $wilayat['name_en'], 'governorate_id' => $governorate->id]
                );
            }
        }
    }
}
