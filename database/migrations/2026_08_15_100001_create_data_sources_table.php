<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * سجل المصادر الموثوقة المسموح لمحرك البادية الذكي جلب البيانات منها.
     */
    public function up(): void
    {
        if (Schema::hasTable('data_sources')) {
            return;
        }

        Schema::create('data_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // اسم المصدر (مثال: البيانات الحكومية المفتوحة - عمان)
            $table->string('slug')->unique();
            $table->enum('type', [
                'government_open_data',
                'official_tourism_authority',
                'licensed_api',
                'official_business_website',
                'business_owner_submission',
                'project_owned',
                'other',
            ])->default('other');
            $table->string('base_url')->nullable();
            $table->unsignedTinyInteger('trust_level')->default(3); // 1 (منخفض) - 5 (رسمي/مضمون)
            $table->boolean('is_active')->default(true);            // هل يُسمح للبوت باستخدامه حاليًا
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_sources');
    }
};
