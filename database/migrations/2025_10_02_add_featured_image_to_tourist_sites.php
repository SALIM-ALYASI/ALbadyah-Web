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
        Schema::table('tourist_sites', function (Blueprint $table) {
            if (!Schema::hasColumn('tourist_sites', 'featured_image')) {
                $table->string('featured_image')->nullable()->after('wilayat_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tourist_sites', function (Blueprint $table) {
            $table->dropColumn(['featured_image']);
        });
    }
};
