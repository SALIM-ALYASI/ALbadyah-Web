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
            // جعل image_url nullable لأنه قد يكون null للصور المحلية
            $table->string('image_url')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tourist_images', function (Blueprint $table) {
            // إرجاع image_url إلى NOT NULL
            $table->string('image_url')->nullable(false)->change();
        });
    }
};
