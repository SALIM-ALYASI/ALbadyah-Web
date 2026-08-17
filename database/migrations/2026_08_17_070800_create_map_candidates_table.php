<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * جدول مسودات بوت البادية - منفصل تمامًا عن tourist_sites/tourist_services.
 * ما يُنشر أي سجل هنا للموقع العام تلقائيًا - مجرد طابور مراجعة بمصادر
 * ودرجة ثقة، والنشر الفعلي (لو صار) خطوة يدوية منفصلة لاحقًا.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('map_candidates', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['site', 'service']);

            $table->string('osm_type')->nullable();
            $table->string('osm_id')->nullable();
            $table->string('wikidata_id')->nullable();

            $table->string('name_ar')->nullable();
            $table->string('name_en')->nullable();
            $table->string('subtype')->nullable();

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('opening_hours')->nullable();

            $table->foreignId('wilayat_id')->nullable()->constrained('wilayats')->nullOnDelete();
            $table->foreignId('governorate_id')->nullable()->constrained('governorates')->nullOnDelete();

            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();

            $table->string('image_url')->nullable();
            $table->string('image_source')->nullable();
            $table->string('image_license')->nullable();
            $table->boolean('image_is_placeholder')->default(false);

            $table->foreignId('tourist_site_category_id')->nullable()->constrained('tourist_site_categories')->nullOnDelete();
            $table->foreignId('service_type_id')->nullable()->constrained('service_types')->nullOnDelete();

            $table->json('sources')->nullable();
            $table->json('field_confidence')->nullable();
            $table->float('overall_confidence')->nullable();
            $table->json('missing_fields')->nullable();

            $table->enum('status', ['pending_review', 'approved_draft', 'rejected', 'deferred'])
                ->default('pending_review');
            $table->string('rejected_reason')->nullable();
            $table->string('telegram_admin_id')->nullable();

            $table->timestamps();

            $table->unique(['osm_type', 'osm_id']);
            $table->index(['wilayat_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('map_candidates');
    }
};
