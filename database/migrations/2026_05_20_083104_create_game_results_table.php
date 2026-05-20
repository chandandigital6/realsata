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
        Schema::create('game_results', function (Blueprint $table) {
         $table->id();
    $table->foreignId('game_id')->constrained()->cascadeOnDelete();
    $table->date('result_date');
    $table->string('result', 10)->nullable();
    $table->enum('status', ['waiting', 'declared'])->default('waiting');
    $table->timestamps();

    $table->unique(['game_id', 'result_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_results');
    }
};
