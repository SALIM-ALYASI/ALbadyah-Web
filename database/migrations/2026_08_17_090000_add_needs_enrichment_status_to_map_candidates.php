<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * فشل Groq يعني "يحتاج إثراء لاحقًا"، مو استبعاد. نضيف حالة صريحة
 * needs_enrichment بدل ما نفقد المرشّح (بياناته الخام من OSM/Wikipedia
 * تبقى محفوظة بالسجل، بس بدون وصف نهائي بعد).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            // SQLite ما يفرض enum فعليًا (نص عادي) - ما فيه شي يتعدّل هنا
            return;
        }

        DB::statement(
            "ALTER TABLE map_candidates MODIFY status "
            ."ENUM('pending_review','needs_enrichment','approved_draft','rejected','deferred') "
            ."NOT NULL DEFAULT 'pending_review'"
        );
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        DB::statement(
            "ALTER TABLE map_candidates MODIFY status "
            ."ENUM('pending_review','approved_draft','rejected','deferred') "
            ."NOT NULL DEFAULT 'pending_review'"
        );
    }
};
