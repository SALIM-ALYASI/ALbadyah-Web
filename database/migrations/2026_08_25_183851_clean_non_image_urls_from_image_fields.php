<?php

use App\Helpers\ImageHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * حقول أُسيء استخدامها سابقًا بتخزين روابط صفحات ويب عادية (موقع الفندق،
     * رابط بحث خرائط جوجل...) بدل روابط صور فعلية، ما يكسر عرضها كصورة.
     * نمسح فقط القيم غير الصورية - لا نلمس أي رابط صورة صالح فعلًا.
     */
    private array $targets = [
        ['table' => 'tourist_services', 'column' => 'image_url'],
        ['table' => 'tourist_sites', 'column' => 'featured_image'],
    ];

    public function up(): void
    {
        foreach ($this->targets as ['table' => $table, 'column' => $column]) {
            DB::table($table)
                ->whereNotNull($column)
                ->where($column, '!=', '')
                ->orderBy('id')
                ->chunkById(200, function ($rows) use ($table, $column) {
                    foreach ($rows as $row) {
                        $value = $row->{$column};

                        if (filter_var($value, FILTER_VALIDATE_URL) && !ImageHelper::looksLikeImageUrl($value)) {
                            DB::table($table)->where('id', $row->id)->update([$column => null]);
                        }
                    }
                });
        }
    }

    /**
     * لا يوجد تراجع - القيم المحذوفة لم تكن روابط صور صحيحة أصلًا.
     */
    public function down(): void
    {
        //
    }
};
