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
        Schema::create('tourist_image_news', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tourist_site_id');    // رقم الموقع السياحي
            $table->string('image_path');                     // مسار الصورة
            $table->string('image_url')->nullable();          // رابط الصورة
            $table->string('alt_text_ar')->nullable();        // النص البديل بالعربي
            $table->string('alt_text_en')->nullable();        // النص البديل بالإنجليزي
            $table->text('caption_ar')->nullable();           // التسمية التوضيحية بالعربي
            $table->text('caption_en')->nullable();           // التسمية التوضيحية بالإنجليزي
            $table->integer('sort_order')->default(0);        // ترتيب الصورة
            $table->boolean('is_featured')->default(false);   // صورة مميزة
            $table->timestamps();

            // مفاتيح خارجية
            $table->foreign('tourist_site_id')->references('id')->on('tourist_site_news')->onDelete('cascade');
            
            // فهارس
            $table->index(['tourist_site_id']);
            $table->index(['sort_order']);
            $table->index(['is_featured']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tourist_image_news');
    }
};
