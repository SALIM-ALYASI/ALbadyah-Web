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
        Schema::create('visit_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('session_id')->nullable()->index();
            $table->string('fingerprint', 128)->nullable()->index();
            $table->string('ip_hash', 128)->nullable()->index();
            $table->string('user_agent', 512)->nullable();
            $table->string('country', 100)->nullable()->index();
            $table->string('city', 150)->nullable()->index();
            $table->string('path', 255)->nullable()->index();
            $table->string('referer', 255)->nullable();
            $table->boolean('is_unique')->default(false);
            $table->timestamp('visited_at')->useCurrent()->index();
            $table->timestamps();

            $table->index(['visited_at', 'country', 'city'], 'visit_logs_visited_at_location_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_logs');
    }
};

