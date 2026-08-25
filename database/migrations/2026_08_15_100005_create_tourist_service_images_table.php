<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tourist_service_images')) {
            return;
        }

        Schema::create('tourist_service_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tourist_service_id')->constrained('tourist_services')->cascadeOnDelete();
            $table->string('image_url')->nullable();
            $table->string('image_path')->nullable();
            $table->string('alt_text_ar')->nullable();
            $table->string('alt_text_en')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index(['tourist_service_id']);
            $table->index(['sort_order']);
            $table->index(['is_featured']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tourist_service_images');
    }
};
