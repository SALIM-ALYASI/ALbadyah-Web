<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * 3 فنادق منشورة فعليًا بلا service_type_id (اكتُشفت أثناء تدقيق بيانات
     * الموقع) - تُصلَح بمطابقة الاسم فقط، ولا تُلمس أي سجل غير هذي الأسماء
     * الثلاثة كي لا تُكتب فوق تصنيف صحيح موجود مسبقًا في بيئة أخرى.
     */
    private array $names = [
        'فندق كراون بلازا مسقط',
        'ليفاتيو سويتس مسقط',
        'سيتادينز الغبرة مسقط',
    ];

    public function up(): void
    {
        $hotelTypeId = DB::table('service_types')->where('name_ar', 'فندق')->value('id');

        if (!$hotelTypeId) {
            return;
        }

        DB::table('tourist_services')
            ->whereIn('name_ar', $this->names)
            ->whereNull('service_type_id')
            ->update(['service_type_id' => $hotelTypeId]);
    }

    public function down(): void
    {
        DB::table('tourist_services')
            ->whereIn('name_ar', $this->names)
            ->update(['service_type_id' => null]);
    }
};
