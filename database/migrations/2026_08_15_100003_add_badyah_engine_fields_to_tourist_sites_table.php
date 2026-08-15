<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * حقول محرك البادية الذكي: تتبع المصدر، التحقق، درجة الثقة، الإحداثيات.
     *
     * verification_status يكون افتراضيًا "approved" حتى لا يتأثر أي سجل حالي
     * أو أي سجل يُنشأ يدويًا من لوحة التحكم. البوت (Ingest API) هو الوحيد
     * الذي يفرض القيمة "pending" صراحةً عند إدخال بياناته.
     */
    public function up(): void
    {
        Schema::table('tourist_sites', function (Blueprint $table) {
            if (!Schema::hasColumn('tourist_sites', 'tourist_site_category_id')) {
                $table->foreignId('tourist_site_category_id')->nullable()->after('wilayat_id')
                    ->constrained('tourist_site_categories')->nullOnDelete();
            }

            if (!Schema::hasColumn('tourist_sites', 'data_source_id')) {
                $table->foreignId('data_source_id')->nullable()->after('tourist_site_category_id')
                    ->constrained('data_sources')->nullOnDelete();
            }

            if (!Schema::hasColumn('tourist_sites', 'source_url')) {
                $table->string('source_url')->nullable()->after('data_source_id');
            }
            if (!Schema::hasColumn('tourist_sites', 'source_name')) {
                $table->string('source_name')->nullable()->after('source_url');
            }
            if (!Schema::hasColumn('tourist_sites', 'source_type')) {
                $table->string('source_type')->nullable()->after('source_name');
            }
            if (!Schema::hasColumn('tourist_sites', 'external_id')) {
                $table->string('external_id')->nullable()->after('source_type');
            }

            if (!Schema::hasColumn('tourist_sites', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('external_id');
            }
            if (!Schema::hasColumn('tourist_sites', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }

            if (!Schema::hasColumn('tourist_sites', 'verification_status')) {
                $table->enum('verification_status', ['pending', 'approved', 'rejected', 'needs_review'])
                    ->default('approved')->after('longitude');
            }
            if (!Schema::hasColumn('tourist_sites', 'needs_review_fields')) {
                $table->json('needs_review_fields')->nullable()->after('verification_status');
            }
            if (!Schema::hasColumn('tourist_sites', 'last_verified_at')) {
                $table->timestamp('last_verified_at')->nullable()->after('needs_review_fields');
            }
            if (!Schema::hasColumn('tourist_sites', 'ai_generated')) {
                $table->boolean('ai_generated')->default(false)->after('last_verified_at');
            }
            if (!Schema::hasColumn('tourist_sites', 'confidence_score')) {
                $table->decimal('confidence_score', 5, 2)->nullable()->after('ai_generated');
            }
            if (!Schema::hasColumn('tourist_sites', 'reviewed_by')) {
                $table->string('reviewed_by')->nullable()->after('confidence_score');
            }
            if (!Schema::hasColumn('tourist_sites', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            }
            if (!Schema::hasColumn('tourist_sites', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('reviewed_at');
            }
        });

        Schema::table('tourist_sites', function (Blueprint $table) {
            $table->unique(['data_source_id', 'external_id'], 'tourist_sites_source_external_unique');
            $table->index(['verification_status']);
            $table->index(['latitude', 'longitude']);
        });

        // كل السجلات الحالية (التي أُدخلت يدويًا قبل وجود محرك البادية) تُعتبر معتمدة أصلاً
        DB::table('tourist_sites')->whereNull('verification_status')->update(['verification_status' => 'approved']);
    }

    public function down(): void
    {
        Schema::table('tourist_sites', function (Blueprint $table) {
            $table->dropUnique('tourist_sites_source_external_unique');
            $table->dropIndex(['verification_status']);
            $table->dropIndex(['latitude', 'longitude']);
            $table->dropConstrainedForeignId('tourist_site_category_id');
            $table->dropConstrainedForeignId('data_source_id');
            $table->dropColumn([
                'source_url', 'source_name', 'source_type', 'external_id',
                'latitude', 'longitude', 'verification_status', 'needs_review_fields',
                'last_verified_at', 'ai_generated', 'confidence_score',
                'reviewed_by', 'reviewed_at', 'rejection_reason',
            ]);
        });
    }
};
