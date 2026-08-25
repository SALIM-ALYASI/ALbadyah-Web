<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $types = [
        ['name_ar' => 'فندق', 'name_en' => 'Hotel', 'placeholder_image' => 'hotel.png'],
        ['name_ar' => 'محطة وقود', 'name_en' => 'Fuel Station', 'placeholder_image' => 'fuel-station.png'],
        ['name_ar' => 'محطة شحن سيارات كهربائية', 'name_en' => 'EV Charging Station', 'placeholder_image' => 'ev-charging.png'],
        ['name_ar' => 'مستشفى', 'name_en' => 'Hospital', 'placeholder_image' => 'hospital.png'],
        ['name_ar' => 'عيادة', 'name_en' => 'Clinic', 'placeholder_image' => 'clinic.png'],
        ['name_ar' => 'صيدلية', 'name_en' => 'Pharmacy', 'placeholder_image' => 'pharmacy.png'],
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->types as $type) {
            $existing = DB::table('service_types')->where('name_ar', $type['name_ar'])->first();

            if ($existing) {
                DB::table('service_types')->where('id', $existing->id)
                    ->update(['placeholder_image' => $type['placeholder_image']]);
                continue;
            }

            DB::table('service_types')->insert([
                'name_ar' => $type['name_ar'],
                'name_en' => $type['name_en'],
                'placeholder_image' => $type['placeholder_image'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('service_types')
            ->whereIn('name_ar', array_column($this->types, 'name_ar'))
            ->update(['placeholder_image' => null]);
    }
};
