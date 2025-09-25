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
        // تحديث جدول المحافظات
        Schema::table('governorates', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('image_url');
        });

        // تحديث جدول الولايات
        Schema::table('wilayats', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('image_url');
        });

        // تحديث جدول الخدمات السياحية
        Schema::table('tourist_services', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('image_url');
        });

        // تحديث جدول صور المواقع السياحية
        Schema::table('tourist_images', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('image_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('governorates', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('wilayats', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('tourist_services', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('tourist_images', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }
};