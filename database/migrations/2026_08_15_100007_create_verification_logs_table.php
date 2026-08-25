<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * سجل تدقيق كامل لكل تغيير في حالة التحقق/الاعتماد لأي سجل
     * (موقع سياحي أو خدمة سياحية) — polymorphic حتى يخدم النوعين معًا.
     */
    public function up(): void
    {
        if (Schema::hasTable('verification_logs')) {
            return;
        }

        Schema::create('verification_logs', function (Blueprint $table) {
            $table->id();
            $table->morphs('recordable'); // recordable_type, recordable_id
            $table->foreignId('import_job_id')->nullable()->constrained('import_jobs')->nullOnDelete();
            $table->enum('action', [
                'created', 'updated', 'auto_verified', 'flagged_needs_review',
                'approved', 'rejected', 'reactivated',
            ]);
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->json('changed_fields')->nullable();
            $table->enum('actor_type', ['system', 'ai', 'admin'])->default('system');
            $table->string('actor_name')->nullable(); // مثال: badyah-bot / اسم المستخدم الإداري
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['action']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verification_logs');
    }
};
