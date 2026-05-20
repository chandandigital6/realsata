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
        Schema::create('chart_years', function (Blueprint $table) {
           $table->id();
    $table->foreignId('game_id')->constrained()->cascadeOnDelete();
    $table->year('year');
    $table->boolean('is_active')->default(true);
    $table->timestamps();

    $table->unique(['game_id', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chart_years');
    }
};
