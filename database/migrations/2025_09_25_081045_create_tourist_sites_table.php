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
        Schema::create('tourist_sites', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');       // اسم الموقع بالعربي
            $table->string('name_en');       // اسم الموقع بالإنجليزي
            $table->text('description_ar');  // وصف بالعربي
            $table->text('description_en');  // وصف بالإنجليزي
            $table->string('location')->nullable();    // الموقع الجغرافي
            $table->string('website_url')->nullable(); // رابط الموقع
            $table->unsignedBigInteger('governorate_id')->nullable(); // رقم المحافظة
            $table->unsignedBigInteger('wilayat_id')->nullable();     // رقم الولاية
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tourist_sites');
    }
};
