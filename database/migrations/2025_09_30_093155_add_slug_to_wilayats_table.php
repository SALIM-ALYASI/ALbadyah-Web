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
        Schema::table('wilayats', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name_en');
        });
        
        // إنشاء slugs للبيانات الموجودة
        $wilayats = \App\Models\Wilayat::all();
        foreach ($wilayats as $wilayat) {
            $wilayat->slug = \App\Models\Wilayat::generateUniqueSlug($wilayat->name_ar);
            $wilayat->save();
        }
        
        // إضافة unique constraint بعد ملء البيانات
        Schema::table('wilayats', function (Blueprint $table) {
            $table->string('slug')->unique()->change();
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wilayats', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropColumn('slug');
        });
    }
};
