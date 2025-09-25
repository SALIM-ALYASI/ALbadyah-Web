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
        Schema::create('save_visits', function (Blueprint $table) {
            $table->id();
            $table->string('country', 100)->nullable(); // الدولة
            $table->string('city', 150)->nullable();    // المدينة
            $table->timestamps();
    
            $table->index(['country', 'city']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('save_visits');
    }
};
