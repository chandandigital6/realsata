<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameResultApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/games-results', [GameResultApiController::class, 'index']);
Route::get('/live-results', [GameResultApiController::class, 'live']);