<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tourist_services', function (Blueprint $table) {
            if (!Schema::hasColumn('tourist_services', 'name_ar_source')) {
                $table->enum('name_ar_source', ['official', 'ai_translation', 'untranslated'])
                    ->nullable()->after('name_ar_verified');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tourist_services', function (Blueprint $table) {
            $table->dropColumn('name_ar_source');
        });
    }
};
