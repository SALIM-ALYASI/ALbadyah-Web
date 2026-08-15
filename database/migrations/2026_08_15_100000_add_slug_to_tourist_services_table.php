<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * إصلاح: نموذج TouristService يعتمد على عمود slug (توليد تلقائي + فريد)
     * لكنه لم يكن موجودًا في الجدول أصلاً، ما يجعل إنشاء أي خدمة سياحية يفشل.
     */
    public function up(): void
    {
        Schema::table('tourist_services', function (Blueprint $table) {
            if (!Schema::hasColumn('tourist_services', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('name_en');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tourist_services', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
