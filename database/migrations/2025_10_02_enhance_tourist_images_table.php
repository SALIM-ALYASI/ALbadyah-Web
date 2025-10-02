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
        Schema::table('tourist_images', function (Blueprint $table) {
            // إضافة حقول جديدة إذا لم تكن موجودة
            if (!Schema::hasColumn('tourist_images', 'image_path')) {
                $table->string('image_path')->nullable()->after('image_url');
            }
            
            if (!Schema::hasColumn('tourist_images', 'alt_text_ar')) {
                $table->string('alt_text_ar')->nullable()->after('image_path');
            }
            
            if (!Schema::hasColumn('tourist_images', 'alt_text_en')) {
                $table->string('alt_text_en')->nullable()->after('alt_text_ar');
            }
            
            if (!Schema::hasColumn('tourist_images', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('alt_text_en');
            }
            
            if (!Schema::hasColumn('tourist_images', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('sort_order');
            }
            
            // إضافة فهارس
            if (!Schema::hasIndex('tourist_images', ['tourist_site_id'])) {
                $table->index(['tourist_site_id']);
            }
            
            if (!Schema::hasIndex('tourist_images', ['sort_order'])) {
                $table->index(['sort_order']);
            }
            
            if (!Schema::hasIndex('tourist_images', ['is_featured'])) {
                $table->index(['is_featured']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tourist_images', function (Blueprint $table) {
            $table->dropIndex(['tourist_site_id']);
            $table->dropIndex(['sort_order']);
            $table->dropIndex(['is_featured']);
            $table->dropColumn(['image_path', 'alt_text_ar', 'alt_text_en', 'sort_order', 'is_featured']);
        });
    }
};
