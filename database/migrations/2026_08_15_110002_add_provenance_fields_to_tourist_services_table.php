<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tourist_services', function (Blueprint $table) {
            if (!Schema::hasColumn('tourist_services', 'source_confidence')) {
                $table->decimal('source_confidence', 5, 2)->nullable()->after('confidence_score');
            }
            if (!Schema::hasColumn('tourist_services', 'ai_confidence')) {
                $table->decimal('ai_confidence', 5, 2)->nullable()->after('source_confidence');
            }
            if (!Schema::hasColumn('tourist_services', 'coordinates_source')) {
                $table->string('coordinates_source')->nullable()->after('longitude');
            }
            if (!Schema::hasColumn('tourist_services', 'is_tourism_candidate')) {
                $table->boolean('is_tourism_candidate')->default(true)->after('is_active');
            }
            if (!Schema::hasColumn('tourist_services', 'excluded_reason')) {
                $table->string('excluded_reason')->nullable()->after('is_tourism_candidate');
            }
            if (!Schema::hasColumn('tourist_services', 'collected_at')) {
                $table->timestamp('collected_at')->nullable()->after('last_verified_at');
            }
            if (!Schema::hasColumn('tourist_services', 'source_last_checked_at')) {
                $table->timestamp('source_last_checked_at')->nullable()->after('collected_at');
            }
            if (!Schema::hasColumn('tourist_services', 'collector_name')) {
                $table->string('collector_name')->nullable()->after('source_last_checked_at');
            }
            if (!Schema::hasColumn('tourist_services', 'collector_version')) {
                $table->string('collector_version')->nullable()->after('collector_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tourist_services', function (Blueprint $table) {
            $table->dropColumn([
                'source_confidence', 'ai_confidence', 'coordinates_source',
                'is_tourism_candidate', 'excluded_reason',
                'collected_at', 'source_last_checked_at', 'collector_name', 'collector_version',
            ]);
        });
    }
};
