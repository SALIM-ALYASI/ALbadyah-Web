<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * سجل تشغيلات محرك البادية (كل تشغيل لـ n8n workflow ينشئ سجل هنا).
     */
    public function up(): void
    {
        Schema::create('import_jobs', function (Blueprint $table) {
            $table->id();
            $table->uuid('job_uuid')->unique();
            $table->string('workflow_name');    // مثال: badyah.site_discovery, badyah.services, badyah.reverify
            $table->string('record_type');      // tourist_site | tourist_service
            $table->foreignId('governorate_id')->nullable()->constrained('governorates')->nullOnDelete();
            $table->foreignId('wilayat_id')->nullable()->constrained('wilayats')->nullOnDelete();
            $table->enum('status', ['queued', 'running', 'completed', 'failed'])->default('queued');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->unsignedInteger('total_fetched')->default(0);
            $table->unsignedInteger('total_created')->default(0);
            $table->unsignedInteger('total_updated')->default(0);
            $table->unsignedInteger('total_duplicates')->default(0);
            $table->unsignedInteger('total_rejected')->default(0);
            $table->unsignedInteger('total_failed')->default(0);
            $table->text('error_message')->nullable();
            $table->json('meta')->nullable(); // أي تفاصيل إضافية حرة (اسم المصدر، معاملات الطلب...)
            $table->timestamps();

            $table->index(['workflow_name']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_jobs');
    }
};
