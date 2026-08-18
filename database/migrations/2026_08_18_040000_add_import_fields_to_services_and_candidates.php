<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('map_candidates', function (Blueprint $table) {
            if (!Schema::hasColumn('map_candidates', 'address_ar')) {
                $table->string('address_ar', 500)->nullable()->after('opening_hours');
            }
        });

        Schema::table('tourist_services', function (Blueprint $table) {
            if (!Schema::hasColumn('tourist_services', 'address_ar')) {
                $table->string('address_ar', 500)->nullable()->after('website_url');
            }
            if (!Schema::hasColumn('tourist_services', 'opening_hours')) {
                $table->string('opening_hours', 255)->nullable()->after('phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tourist_services', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('tourist_services', 'address_ar')) {
                $columns[] = 'address_ar';
            }
            if (Schema::hasColumn('tourist_services', 'opening_hours')) {
                $columns[] = 'opening_hours';
            }
            if ($columns) {
                $table->dropColumn($columns);
            }
        });

        Schema::table('map_candidates', function (Blueprint $table) {
            if (Schema::hasColumn('map_candidates', 'address_ar')) {
                $table->dropColumn('address_ar');
            }
        });
    }
};
