<?php

namespace App\Http\Controllers;
use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Http\Request;
class GameResultApiController extends Controller
{
   


    public function index(Request $request)
    {
        $date = $request->date ?? today()->format('Y-m-d');

        $games = Game::with([
                'results' => function ($q) use ($date) {
                    $q->whereDate('result_date', $date);
                }
            ])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $data = $games->map(function ($game) {
            $todayResult = $game->results->first();

            return [
                'id'          => $game->id,
                'name'        => $game->name,
                'slug'        => $game->slug,
                'result_time' => $game->result_time,
                'sort_order'  => $game->sort_order,
                'is_active'   => $game->is_active,

                'result' => [
                    'id'           => $todayResult?->id,
                    'result_date'  => $todayResult?->result_date?->format('Y-m-d'),
                    'result'       => $todayResult?->result,
                    'status'       => $todayResult?->status ?? 'waiting',
                    'show_minutes' => $todayResult?->show_minutes ?? 0,
                    'updated_at'   => $todayResult?->updated_at?->format('Y-m-d H:i:s'),
                ],
            ];
        });

        return response()->json([
            'success' => true,
            'date'    => $date,
            'games'   => $data,
        ]);
    }

    public function live()
    {
        $now = Carbon::now('Asia/Kolkata');
        $today = $now->format('Y-m-d');

        $games = Game::with([
                'results' => function ($q) use ($today) {
                    $q->whereDate('result_date', $today);
                }
            ])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $data = $games->map(function ($game) use ($now) {
            $todayResult = $game->results->first();

            $isDeclared = $todayResult
                && $todayResult->status === 'declared'
                && !empty($todayResult->result);

            return [
                'id'          => $game->id,
                'name'        => $game->name,
                'slug'        => $game->slug,
                'result_time' => $game->result_time,

                'result'      => $isDeclared ? $todayResult->result : null,
                'status'      => $isDeclared ? 'declared' : 'waiting',

                'updated_at'  => $todayResult?->updated_at?->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'success' => true,
            'date'    => $today,
            'games'   => $data,
        ]);
    }



    public function chartGames()
{
    $games = \App\Models\Game::query()
        ->where('is_active', true)
        ->with(['chartYears' => function ($query) {
            $query->where('is_active', true)->orderByDesc('year');
        }])
        ->orderBy('sort_order')
        ->get()
        ->map(function ($game) {
            return [
                'id' => $game->id,
                'name' => $game->name,
                'slug' => $game->slug,
                'result_time' => $game->result_time,
                'chartYears' => $game->chartYears->map(function ($year) {
                    return [
                        'year' => $year->year,
                    ];
                })->values(),
            ];
        });

    return response()->json([
        'success' => true,
        'games' => $games,
    ]);
}

public function gameYearRecord(string $slug, int $year)
{
    $game = \App\Models\Game::where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

    $results = \App\Models\GameResult::where('game_id', $game->id)
        ->whereYear('result_date', $year)
        ->orderBy('result_date')
        ->get()
        ->map(function ($result) {
            return [
                'result_date' => $result->result_date?->format('Y-m-d'),
                'result' => $result->result,
                'status' => $result->status,
            ];
        });

    return response()->json([
        'success' => true,
        'game' => [
            'id' => $game->id,
            'name' => $game->name,
            'slug' => $game->slug,
            'result_time' => $game->result_time,
        ],
        'year' => $year,
        'results' => $results,
    ]);
}


}
