<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('map_candidates', function (Blueprint $table) {
            if (!Schema::hasColumn('map_candidates', 'published_table')) {
                $table->string('published_table', 40)->nullable()->after('telegram_admin_id');
            }
            if (!Schema::hasColumn('map_candidates', 'published_id')) {
                $table->unsignedBigInteger('published_id')->nullable()->after('published_table');
            }
            if (!Schema::hasColumn('map_candidates', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('published_id');
            }
        });

        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            DB::statement(
                "ALTER TABLE map_candidates MODIFY status "
                ."ENUM('pending_review','needs_enrichment','approved_draft','rejected','deferred','published') "
                ."NOT NULL DEFAULT 'pending_review'"
            );
        }
    }

    public function down(): void
    {
        DB::table('map_candidates')->where('status', 'published')->update([
            'status' => 'approved_draft',
        ]);

        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            DB::statement(
                "ALTER TABLE map_candidates MODIFY status "
                ."ENUM('pending_review','needs_enrichment','approved_draft','rejected','deferred') "
                ."NOT NULL DEFAULT 'pending_review'"
            );
        }

        Schema::table('map_candidates', function (Blueprint $table) {
            $columns = array_values(array_filter(
                ['published_table', 'published_id', 'published_at'],
                fn ($column) => Schema::hasColumn('map_candidates', $column)
            ));
            if ($columns) {
                $table->dropColumn($columns);
            }
        });
    }
};
