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
        Schema::create('tourist_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tourist_site_id'); // رقم الموقع السياحي
            $table->string('image_url');                   // رابط الصورة 
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tourist_images');
    }
};
