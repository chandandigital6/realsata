<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoPage extends Model
{
    protected $guarded = ['id'];


    public function game()
{
    return $this->belongsTo(\App\Models\Game::class);
}
}
