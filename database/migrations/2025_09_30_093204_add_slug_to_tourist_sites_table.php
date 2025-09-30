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
            $table->string('slug')->nullable()->after('name_en');
        });
        
        // إنشاء slugs للبيانات الموجودة
        $touristSites = \App\Models\TouristSite::all();
        foreach ($touristSites as $site) {
            $site->slug = \App\Models\TouristSite::generateUniqueSlug($site->name_ar);
            $site->save();
        }
        
        // إضافة unique constraint بعد ملء البيانات
        Schema::table('tourist_sites', function (Blueprint $table) {
            $table->string('slug')->unique()->change();
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tourist_sites', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropColumn('slug');
        });
    }
};
