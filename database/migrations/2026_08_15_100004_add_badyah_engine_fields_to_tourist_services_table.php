<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tourist_services', function (Blueprint $table) {
            if (!Schema::hasColumn('tourist_services', 'data_source_id')) {
                $table->foreignId('data_source_id')->nullable()->after('service_type_id')
                    ->constrained('data_sources')->nullOnDelete();
            }

            if (!Schema::hasColumn('tourist_services', 'source_url')) {
                $table->string('source_url')->nullable()->after('data_source_id');
            }
            if (!Schema::hasColumn('tourist_services', 'source_name')) {
                $table->string('source_name')->nullable()->after('source_url');
            }
            if (!Schema::hasColumn('tourist_services', 'source_type')) {
                $table->string('source_type')->nullable()->after('source_name');
            }
            if (!Schema::hasColumn('tourist_services', 'external_id')) {
                $table->string('external_id')->nullable()->after('source_type');
            }

            if (!Schema::hasColumn('tourist_services', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('external_id');
            }
            if (!Schema::hasColumn('tourist_services', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }

            if (!Schema::hasColumn('tourist_services', 'phone')) {
                $table->string('phone')->nullable()->after('longitude');
            }
            if (!Schema::hasColumn('tourist_services', 'whatsapp')) {
                $table->string('whatsapp')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('tourist_services', 'description_ar')) {
                $table->text('description_ar')->nullable()->after('whatsapp');
            }
            if (!Schema::hasColumn('tourist_services', 'description_en')) {
                $table->text('description_en')->nullable()->after('description_ar');
            }

            if (!Schema::hasColumn('tourist_services', 'verification_status')) {
                $table->enum('verification_status', ['pending', 'approved', 'rejected', 'needs_review'])
                    ->default('approved')->after('description_en');
            }
            if (!Schema::hasColumn('tourist_services', 'needs_review_fields')) {
                $table->json('needs_review_fields')->nullable()->after('verification_status');
            }
            if (!Schema::hasColumn('tourist_services', 'last_verified_at')) {
                $table->timestamp('last_verified_at')->nullable()->after('needs_review_fields');
            }
            if (!Schema::hasColumn('tourist_services', 'ai_generated')) {
                $table->boolean('ai_generated')->default(false)->after('last_verified_at');
            }
            if (!Schema::hasColumn('tourist_services', 'confidence_score')) {
                $table->decimal('confidence_score', 5, 2)->nullable()->after('ai_generated');
            }
            if (!Schema::hasColumn('tourist_services', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('confidence_score');
            }
            if (!Schema::hasColumn('tourist_services', 'reviewed_by')) {
                $table->string('reviewed_by')->nullable()->after('is_active');
            }
            if (!Schema::hasColumn('tourist_services', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            }
            if (!Schema::hasColumn('tourist_services', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('reviewed_at');
            }
        });

        Schema::table('tourist_services', function (Blueprint $table) {
            $table->unique(['data_source_id', 'external_id'], 'tourist_services_source_external_unique');
            $table->index(['verification_status']);
            $table->index(['latitude', 'longitude']);
        });

        DB::table('tourist_services')->whereNull('verification_status')->update(['verification_status' => 'approved']);
    }

    public function down(): void
    {
        Schema::table('tourist_services', function (Blueprint $table) {
            $table->dropUnique('tourist_services_source_external_unique');
            $table->dropIndex(['verification_status']);
            $table->dropIndex(['latitude', 'longitude']);
            $table->dropConstrainedForeignId('data_source_id');
            $table->dropColumn([
                'source_url', 'source_name', 'source_type', 'external_id',
                'latitude', 'longitude', 'phone', 'whatsapp',
                'description_ar', 'description_en',
                'verification_status', 'needs_review_fields', 'last_verified_at',
                'ai_generated', 'confidence_score', 'is_active',
                'reviewed_by', 'reviewed_at', 'rejection_reason',
            ]);
        });
    }
};
