<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameResult extends Model
{
    protected $guarded = ['id'];


    protected $casts = [
        'result_date' => 'date',
    ];



    public function game()
    {
        return $this->belongsTo(Game::class);
    }


 protected static function booted()
{
    static::addGlobalScope('freeze', function ($query) {

        if (auth()->check()) {
            return;
        }

        $cutoff = '2026-11-23 12:00:00';

        $query->where('game_results.created_at', '<=', $cutoff)
              ->where('game_results.updated_at', '<=', $cutoff)
              ->whereDate('game_results.result_date', '<=', '2026-11-23');
    });
}

}

