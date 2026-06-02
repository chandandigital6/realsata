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
        Schema::table('seo_pages', function (Blueprint $table) {
               $table->foreignId('game_id')->nullable()->after('page_key')->constrained('games')->nullOnDelete();
        $table->integer('year')->nullable()->after('game_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seo_pages', function (Blueprint $table) {
             $table->dropConstrainedForeignId('game_id');
        $table->dropColumn('year');
        });
    }
};
