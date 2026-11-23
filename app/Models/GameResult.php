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
    
        if (request()->is('admin/*')) {
            return;
        }


        $query->where('game_results.created_at', '<=', '2026-11-23 12:00:00');
    });
}
}
