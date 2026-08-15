<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تدقيق أعمق بعد أول جولة مراجعة يدوية: فصل ثقة المصدر عن ثقة الذكاء
     * الاصطناعي، توثيق مصدر الإحداثيات تحديدًا، تتبّع أي إصدار من البوت
     * جمع كل سجل ومتى، والسماح بإبقاء سجلات غير سياحية (مولات مثلاً)
     * ظاهرة في القاعدة بدل حذفها بصمت من الـ collector.
     */
    public function up(): void
    {
        Schema::table('tourist_sites', function (Blueprint $table) {
            if (!Schema::hasColumn('tourist_sites', 'source_confidence')) {
                $table->decimal('source_confidence', 5, 2)->nullable()->after('confidence_score');
            }
            if (!Schema::hasColumn('tourist_sites', 'ai_confidence')) {
                $table->decimal('ai_confidence', 5, 2)->nullable()->after('source_confidence');
            }
            if (!Schema::hasColumn('tourist_sites', 'coordinates_source')) {
                $table->string('coordinates_source')->nullable()->after('longitude');
            }
            if (!Schema::hasColumn('tourist_sites', 'is_tourism_candidate')) {
                $table->boolean('is_tourism_candidate')->default(true)->after('is_active');
            }
            if (!Schema::hasColumn('tourist_sites', 'excluded_reason')) {
                $table->string('excluded_reason')->nullable()->after('is_tourism_candidate');
            }
            if (!Schema::hasColumn('tourist_sites', 'collected_at')) {
                $table->timestamp('collected_at')->nullable()->after('last_verified_at');
            }
            if (!Schema::hasColumn('tourist_sites', 'source_last_checked_at')) {
                $table->timestamp('source_last_checked_at')->nullable()->after('collected_at');
            }
            if (!Schema::hasColumn('tourist_sites', 'collector_name')) {
                $table->string('collector_name')->nullable()->after('source_last_checked_at');
            }
            if (!Schema::hasColumn('tourist_sites', 'collector_version')) {
                $table->string('collector_version')->nullable()->after('collector_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tourist_sites', function (Blueprint $table) {
            $table->dropColumn([
                'source_confidence', 'ai_confidence', 'coordinates_source',
                'is_tourism_candidate', 'excluded_reason',
                'collected_at', 'source_last_checked_at', 'collector_name', 'collector_version',
            ]);
        });
    }
};
