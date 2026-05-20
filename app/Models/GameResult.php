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
}
