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
        Schema::create('tourist_site_news', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');                    // اسم الموقع بالعربي
            $table->string('name_en');                    // اسم الموقع بالإنجليزي
            $table->string('slug')->unique();             // رابط فريد للموقع
            $table->text('description_ar');               // وصف بالعربي
            $table->text('description_en');               // وصف بالإنجليزي
            $table->string('location')->nullable();       // الموقع الجغرافي
            $table->string('website_url')->nullable();    // رابط الموقع
            $table->string('phone')->nullable();          // رقم الهاتف
            $table->string('email')->nullable();          // البريد الإلكتروني
            $table->decimal('latitude', 10, 8)->nullable(); // خط العرض
            $table->decimal('longitude', 11, 8)->nullable(); // خط الطول
            $table->unsignedBigInteger('governorate_id')->nullable(); // رقم المحافظة
            $table->unsignedBigInteger('wilayat_id')->nullable();     // رقم الولاية
            $table->boolean('is_active')->default(true);  // حالة النشاط
            $table->string('featured_image')->nullable(); // الصورة المميزة
            $table->string('meta_title_ar')->nullable();  // عنوان SEO بالعربي
            $table->string('meta_title_en')->nullable();  // عنوان SEO بالإنجليزي
            $table->text('meta_description_ar')->nullable(); // وصف SEO بالعربي
            $table->text('meta_description_en')->nullable(); // وصف SEO بالإنجليزي
            $table->timestamps();

            // فهارس لتحسين الأداء
            $table->index(['governorate_id']);
            $table->index(['wilayat_id']);
            $table->index(['is_active']);
            $table->index(['slug']);
            
            // مفاتيح خارجية
            $table->foreign('governorate_id')->references('id')->on('governorates')->onDelete('set null');
            $table->foreign('wilayat_id')->references('id')->on('wilayats')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tourist_site_news');
    }
};
