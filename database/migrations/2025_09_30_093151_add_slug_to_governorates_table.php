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
        Schema::table('governorates', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name_en');
        });
        
        // إنشاء slugs للبيانات الموجودة
        $governorates = \App\Models\Governorate::all();
        foreach ($governorates as $governorate) {
            $governorate->slug = \App\Models\Governorate::generateUniqueSlug($governorate->name_ar);
            $governorate->save();
        }
        
        // إضافة unique constraint بعد ملء البيانات
        Schema::table('governorates', function (Blueprint $table) {
            $table->string('slug')->unique()->change();
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('governorates', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropColumn('slug');
        });
    }
};
