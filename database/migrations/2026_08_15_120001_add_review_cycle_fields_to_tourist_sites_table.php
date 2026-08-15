<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تعديلات بعد أول جولة مراجعة يدوية:
     * - source_confidence اسمه مضلل (يوحي بصحة المحتوى 100%). أعيد تسميته
     *   source_trust_score: يعني فقط "المصدر رسمي وموثوق"، لا يعني أن كل
     *   حقل بالسجل صحيح فعليًا.
     * - name_ar_verified: false افتراضيًا دائمًا عند الإدخال؛ يتحول true فقط
     *   بعد اعتماد المراجع البشري صراحة عبر دورة المراجعة (Gmail).
     * - description_ar_generated: يوثّق أن الوصف العربي نص AI وليس رسميًا،
     *   بمعزل عن حالة الاسم العربي (قد يكون الاسم رسميًا والوصف لا، أو العكس).
     * - review_requested_at: متى أُرسل هذا السجل فعليًا ضمن دفعة مراجعة بريدية.
     */
    public function up(): void
    {
        Schema::table('tourist_sites', function (Blueprint $table) {
            if (Schema::hasColumn('tourist_sites', 'source_confidence') && !Schema::hasColumn('tourist_sites', 'source_trust_score')) {
                $table->renameColumn('source_confidence', 'source_trust_score');
            }
            if (!Schema::hasColumn('tourist_sites', 'name_ar_verified')) {
                $table->boolean('name_ar_verified')->default(false)->after('ai_confidence');
            }
            if (!Schema::hasColumn('tourist_sites', 'description_ar_generated')) {
                $table->boolean('description_ar_generated')->default(false)->after('name_ar_verified');
            }
            if (!Schema::hasColumn('tourist_sites', 'review_requested_at')) {
                $table->timestamp('review_requested_at')->nullable()->after('source_last_checked_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tourist_sites', function (Blueprint $table) {
            $table->renameColumn('source_trust_score', 'source_confidence');
            $table->dropColumn(['name_ar_verified', 'description_ar_generated', 'review_requested_at']);
        });
    }
};
