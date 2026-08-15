<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tourist_services', function (Blueprint $table) {
            if (Schema::hasColumn('tourist_services', 'source_confidence') && !Schema::hasColumn('tourist_services', 'source_trust_score')) {
                $table->renameColumn('source_confidence', 'source_trust_score');
            }
            if (!Schema::hasColumn('tourist_services', 'name_ar_verified')) {
                $table->boolean('name_ar_verified')->default(false)->after('ai_confidence');
            }
            if (!Schema::hasColumn('tourist_services', 'description_ar_generated')) {
                $table->boolean('description_ar_generated')->default(false)->after('name_ar_verified');
            }
            if (!Schema::hasColumn('tourist_services', 'review_requested_at')) {
                $table->timestamp('review_requested_at')->nullable()->after('source_last_checked_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tourist_services', function (Blueprint $table) {
            $table->renameColumn('source_trust_score', 'source_confidence');
            $table->dropColumn(['name_ar_verified', 'description_ar_generated', 'review_requested_at']);
        });
    }
};
