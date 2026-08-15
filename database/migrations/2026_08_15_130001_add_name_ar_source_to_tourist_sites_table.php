<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * يوثّق من أين جاء الاسم العربي فعليًا (رسمي من المصدر / ترجمة AI /
     * غير مترجم) — تُستخدم لعرض حالة "تحتاج تأكيد" الصحيحة بصفحة المراجعة،
     * ولتحديد ما إذا كان name_ar_verified يمكن أن يصبح true تلقائيًا.
     */
    public function up(): void
    {
        Schema::table('tourist_sites', function (Blueprint $table) {
            if (!Schema::hasColumn('tourist_sites', 'name_ar_source')) {
                $table->enum('name_ar_source', ['official', 'ai_translation', 'untranslated'])
                    ->nullable()->after('name_ar_verified');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tourist_sites', function (Blueprint $table) {
            $table->dropColumn('name_ar_source');
        });
    }
};
