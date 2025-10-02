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
            // التحقق من وجود الحقول قبل إضافتها
            if (!Schema::hasColumn('tourist_sites', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('wilayat_id');
                $table->index(['is_active']);
            }
            
            if (!Schema::hasColumn('tourist_sites', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('name_en');
                $table->index(['slug']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tourist_sites', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['slug']);
            $table->dropColumn(['is_active', 'slug']);
        });
    }
};
