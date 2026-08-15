<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * إصلاح: نموذج Governorate يعتمد على عمود slug (توليد تلقائي + فريد)
     * لكنه لم يكن موجودًا في الجدول أصلاً، ما يجعل إنشاء أي محافظة يفشل.
     */
    public function up(): void
    {
        Schema::table('governorates', function (Blueprint $table) {
            if (!Schema::hasColumn('governorates', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('name_en');
            }
        });
    }

    public function down(): void
    {
        Schema::table('governorates', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
