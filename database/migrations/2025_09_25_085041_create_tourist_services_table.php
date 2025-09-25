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
        Schema::create('tourist_services', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('website_url')->nullable();
            $table->string('image_url')->nullable();
        
            $table->unsignedBigInteger('governorate_id')->nullable();
            $table->unsignedBigInteger('wilayat_id')->nullable();
        
            // النوع كمرجع لجدول service_types (بدون قيود FK إن تحب)
            $table->unsignedBigInteger('service_type_id')->nullable();
        
            $table->timestamps();
         
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tourist_services');
    }
};
