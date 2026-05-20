<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChartYear extends Model
{
    protected $guarded = ['id'];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
