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
        Schema::create('governorates', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar')->nullable();     // الاسم بالعربية
            $table->string('name_en')->nullable();     // الاسم بالإنجليزية
            $table->string('website_url')->nullable(); // رابط موقع المحافظة
            $table->string('image_url')->nullable();   // رابط صورة المحافظة
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('governorates');
    }
};
