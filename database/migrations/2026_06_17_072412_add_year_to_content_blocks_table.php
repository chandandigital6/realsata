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
        Schema::table('content_blocks', function (Blueprint $table) {
            if (!Schema::hasColumn('content_blocks', 'year')) {
                $table->integer('year')->nullable()->after('game_id');
            }
        });

        Schema::table('content_blocks', function (Blueprint $table) {
            $table->index(['game_id', 'year'], 'content_blocks_game_id_year_index');
        });
    }

    public function down(): void
    {
        Schema::table('content_blocks', function (Blueprint $table) {
            $table->dropIndex('content_blocks_game_id_year_index');
        });

        Schema::table('content_blocks', function (Blueprint $table) {
            if (Schema::hasColumn('content_blocks', 'year')) {
                $table->dropColumn('year');
            }
        });
    }
};
