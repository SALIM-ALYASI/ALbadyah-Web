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
        Schema::create('wilayats', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');      // اسم الولاية بالعربي
            $table->string('name_en');      // اسم الولاية بالإنجليزي
            $table->string('website_url')->nullable(); // رابط موقع الولاية
            $table->string('image_url')->nullable();   // رابط صورة الولاية
            $table->unsignedBigInteger('governorate_id')->nullable(); // رقم المحافظة فقط بدون علاقة
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wilayats');
    }
};
