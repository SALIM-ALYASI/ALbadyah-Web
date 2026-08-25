<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('service_types', function (Blueprint $table) {
            // اسم ملف الصورة الافتراضية بمجلد public/images/service-placeholders
            // (تُستخدم لأي خدمة من هذا النوع بلا صورة حقيقية بعد)
            $table->string('placeholder_image')->nullable()->after('name_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_types', function (Blueprint $table) {
            $table->dropColumn('placeholder_image');
        });
    }
};
