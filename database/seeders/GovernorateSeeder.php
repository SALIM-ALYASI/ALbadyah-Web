<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Governorate;

class GovernorateSeeder extends Seeder
{
    /**
     * المحافظات الإحدى عشرة الرسمية لسلطنة عُمان.
     * idempotent: يطابق بالاسم العربي فقط، فيحدّث الموجود بدل تكراره
     * (مهم لمحافظة مسقط الموجودة أصلاً بقاعدة بيانات الإنتاج).
     */
    public function run(): void
    {
        $governorates = [
            ['name_ar' => 'مسقط', 'name_en' => 'Muscat'],
            ['name_ar' => 'الداخلية', 'name_en' => 'Ad Dakhiliyah'],
            ['name_ar' => 'شمال الباطنة', 'name_en' => 'North Al Batinah'],
            ['name_ar' => 'جنوب الباطنة', 'name_en' => 'South Al Batinah'],
            ['name_ar' => 'الوسطى', 'name_en' => 'Al Wusta'],
            ['name_ar' => 'شمال الشرقية', 'name_en' => 'North Ash Sharqiyah'],
            ['name_ar' => 'جنوب الشرقية', 'name_en' => 'South Ash Sharqiyah'],
            ['name_ar' => 'الظاهرة', 'name_en' => 'Ad Dhahirah'],
            ['name_ar' => 'مسندم', 'name_en' => 'Musandam'],
            ['name_ar' => 'ظفار', 'name_en' => 'Dhofar'],
            ['name_ar' => 'البريمي', 'name_en' => 'Al Buraimi'],
        ];

        foreach ($governorates as $governorate) {
            Governorate::updateOrCreate(
                ['name_ar' => $governorate['name_ar']],
                ['name_en' => $governorate['name_en']]
            );
        }
    }
}
