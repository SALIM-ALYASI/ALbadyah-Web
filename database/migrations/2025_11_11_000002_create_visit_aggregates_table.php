<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('visit_aggregates')) {
            return;
        }

        Schema::create('visit_aggregates', function (Blueprint $table) {
            $table->id();
            $table->date('date')->index();
            $table->string('country', 100)->nullable()->index();
            $table->string('city', 150)->nullable()->index();
            $table->string('path', 255)->nullable()->index();
            $table->unsignedBigInteger('visits_count')->default(0);
            $table->unsignedBigInteger('unique_visits_count')->default(0);
            $table->timestamps();

            $table->unique(['date', 'country', 'city', 'path'], 'visit_aggregates_unique_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_aggregates');
    }
};

