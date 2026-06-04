<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $guarded  = [
       'id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];



    public function users()
{
    return $this->belongsToMany(\App\Models\User::class, 'game_user');
}

    public function results()
    {
        return $this->hasMany(GameResult::class);
    }

    public function todayResult()
    {
        return $this->hasOne(GameResult::class)
            ->whereDate('result_date', today());
    }

    public function yesterdayResult()
    {
        return $this->hasOne(GameResult::class)
            ->whereDate('result_date', today()->subDay());
    }

    public function latestResult()
    {
        return $this->hasOne(GameResult::class)
            ->latestOfMany('result_date');
    }


    public function chartYears()
{
    return $this->hasMany(ChartYear::class);
}
}
