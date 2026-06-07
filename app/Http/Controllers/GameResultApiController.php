<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameResult;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GameResultApiController extends Controller
{
   



public function index(Request $request)
{
    $date = $request->date ?? Carbon::today('Asia/Kolkata')->format('Y-m-d');

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
            'is_active'   => (bool) $game->is_active,

            'result' => [
                'id' => $todayResult?->id,

                'result_date' => $todayResult?->result_date
                    ? Carbon::parse($todayResult->result_date)->format('Y-m-d')
                    : null,

                // IMPORTANT: yaha result hide/null nahi karna hai
                'result' => $todayResult?->result,

                'status' => $todayResult?->status ?? 'waiting',

                'show_minutes' => !empty($todayResult?->show_minutes)
                    ? (int) $todayResult->show_minutes
                    : 10,

                'updated_at' => $todayResult?->updated_at
                    ? Carbon::parse($todayResult->updated_at)
                        ->timezone('Asia/Kolkata')
                        ->format('Y-m-d H:i:s')
                    : null,
            ],
        ];
    })->values();

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

        $showMinutes = !empty($todayResult?->show_minutes)
            ? (int) $todayResult->show_minutes
            : 10;

        $updatedAt = $todayResult?->updated_at
            ? Carbon::parse($todayResult->updated_at)->timezone('Asia/Kolkata')
            : null;

        $isDeclared = $todayResult
            && $todayResult->status === 'declared'
            && !empty($todayResult->result)
            && $updatedAt;

        $isLive = false;

        if ($isDeclared) {
            $isLive = $now->lessThanOrEqualTo(
                $updatedAt->copy()->addMinutes($showMinutes)
            );
        }

        $gameDateTime = null;

        if (!empty($game->result_time)) {
            try {
                $gameDateTime = Carbon::parse(
                    $now->format('Y-m-d') . ' ' . trim($game->result_time),
                    'Asia/Kolkata'
                );
            } catch (\Throwable $e) {
                $gameDateTime = null;
            }
        }

        return [
            'id'          => $game->id,
            'name'        => $game->name,
            'slug'        => $game->slug,
            'result_time' => $game->result_time,
            'sort_order'  => $game->sort_order,
            'is_active'   => (bool) $game->is_active,

            'is_live_declared' => $isLive,
            'updated_timestamp' => $updatedAt?->timestamp,
            'game_timestamp' => $gameDateTime?->timestamp,

            'result' => [
                'id'           => $todayResult?->id,
                'result_date'  => $todayResult?->result_date
                    ? Carbon::parse($todayResult->result_date)->format('Y-m-d')
                    : null,
                'result'       => $isLive ? $todayResult->result : null,
                'status'       => $isLive ? 'declared' : 'waiting',
                'show_minutes' => $showMinutes,
                'updated_at'   => $updatedAt?->format('Y-m-d H:i:s'),
                'is_live'      => $isLive,
            ],
        ];
    });

    $declaredGames = $data
        ->filter(fn ($game) => $game['is_live_declared'] === true)
        ->sortByDesc('updated_timestamp')
        ->values();

    $normalGames = $data
        ->reject(fn ($game) => $game['is_live_declared'] === true)
        ->filter(fn ($game) => !empty($game['game_timestamp']) && $game['game_timestamp'] >= $now->timestamp)
        ->sortBy('game_timestamp')
        ->values();

    $finalGames = $declaredGames
        ->concat($normalGames)
        ->take(4)
        ->values()
        ->map(function ($game) {
            unset($game['is_live_declared'], $game['updated_timestamp'], $game['game_timestamp']);
            return $game;
        });

    return response()->json([
        'success' => true,
        'date'    => $today,
        'games'   => $finalGames,
    ]);
}


    public function liveold()
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

            $showMinutes = !empty($todayResult?->show_minutes)
                ? (int) $todayResult->show_minutes
                : 10;

            $updatedAt = $todayResult?->updated_at
                ? Carbon::parse($todayResult->updated_at)->timezone('Asia/Kolkata')
                : null;

            $isDeclared = $todayResult
                && $todayResult->status === 'declared'
                && !empty($todayResult->result);

            $isLive = false;

            if ($isDeclared && $updatedAt) {
                $isLive = $now->lessThanOrEqualTo($updatedAt->copy()->addMinutes($showMinutes));
            }

            return [
                'id'          => $game->id,
                'name'        => $game->name,
                'slug'        => $game->slug,
                'result_time' => $game->result_time,
                'sort_order'  => $game->sort_order,

                'result' => [
                    'id'           => $todayResult?->id,
                    'result_date'  => $todayResult?->result_date
                        ? Carbon::parse($todayResult->result_date)->format('Y-m-d')
                        : null,
                    'result'       => $isLive ? $todayResult->result : null,
                    'status'       => $isLive ? 'declared' : 'waiting',
                    'show_minutes' => $showMinutes,
                    'updated_at'   => $updatedAt?->format('Y-m-d H:i:s'),
                    'is_live'      => $isLive,
                ],
            ];
        })->values();

        return response()->json([
            'success' => true,
            'date'    => $today,
            'games'   => $data,
        ]);
    }

    public function chartGames()
    {
        $games = Game::query()
            ->where('is_active', true)
            ->with(['chartYears' => function ($query) {
                $query->where('is_active', true)->orderByDesc('year');
            }])
            ->orderBy('sort_order')
            ->get()
            ->map(function ($game) {
                return [
                    'id'          => $game->id,
                    'name'        => $game->name,
                    'slug'        => $game->slug,
                    'result_time' => $game->result_time,
                    'sort_order'  => $game->sort_order,
                    'chartYears'  => $game->chartYears->map(function ($year) {
                        return [
                            'year' => $year->year,
                        ];
                    })->values(),
                ];
            })->values();

        return response()->json([
            'success' => true,
            'games' => $games,
        ]);
    }

    public function gameYearRecord(string $slug, int $year)
    {
        $game = Game::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $results = GameResult::where('game_id', $game->id)
            ->whereYear('result_date', $year)
            ->orderBy('result_date')
            ->get()
            ->map(function ($result) {
                return [
                    'result_date' => $result->result_date
                        ? Carbon::parse($result->result_date)->format('Y-m-d')
                        : null,
                    'result' => $result->result,
                    'status' => $result->status,
                ];
            })->values();

        return response()->json([
            'success' => true,
            'game' => [
                'id'          => $game->id,
                'name'        => $game->name,
                'slug'        => $game->slug,
                'result_time' => $game->result_time,
            ],
            'year'    => $year,
            'results' => $results,
        ]);
    }







public function homeLiveResultsWOrking(Request $request)
{
    $now = Carbon::now('Asia/Kolkata');
    $today = $now->format('Y-m-d');

    $limit = max(1, min((int) $request->get('limit', 4), 20));

    $games = Game::with([
            'results' => function ($q) use ($today) {
                $q->whereDate('result_date', $today)
                    ->latest('updated_at');
            }
        ])
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    $data = $games->map(function ($game) use ($now) {
        $todayResult = $game->results->first();

        $isDeclared = $todayResult
            && $todayResult->status === 'declared'
            && filled($todayResult->result);

        $showMinutes = $todayResult && filled($todayResult->show_minutes)
            ? (int) $todayResult->show_minutes
            : 10;

        $updatedAt = $todayResult?->updated_at
            ? Carbon::parse($todayResult->updated_at)->timezone('Asia/Kolkata')
            : null;

        $isLive = false;

        if ($isDeclared) {
            if ($showMinutes <= 0) {
                $isLive = true;
            } elseif ($updatedAt) {
                $isLive = $now->lessThanOrEqualTo(
                    $updatedAt->copy()->addMinutes($showMinutes)
                );
            }
        }

        $gameDateTime = null;

        if (filled($game->result_time)) {
            try {
                $gameDateTime = Carbon::parse(
                    $now->format('Y-m-d') . ' ' . trim($game->result_time),
                    'Asia/Kolkata'
                );
            } catch (\Throwable $e) {
                $gameDateTime = null;
            }
        }

        return [
            'id' => $game->id,
            'name' => $game->name,
            'slug' => $game->slug,
            'result_time' => $game->result_time,
            'sort_order' => $game->sort_order,

            '_is_declared' => $isDeclared,
            '_is_live_declared' => $isLive,
            '_updated_time' => $updatedAt?->timestamp,
            '_game_time' => $gameDateTime?->timestamp,

            'result' => [
                'id' => $todayResult?->id,
                'result_date' => $todayResult?->result_date
                    ? Carbon::parse($todayResult->result_date)->format('Y-m-d')
                    : null,
                'result' => $isLive ? $todayResult->result : null,
                'status' => $isLive ? 'declared' : 'waiting',
                'show_minutes' => $showMinutes,
                'updated_at' => $updatedAt?->format('Y-m-d H:i:s'),
                'is_live' => $isLive,
            ],
        ];
    });

    $declaredGames = $data
        ->filter(fn ($game) => $game['_is_live_declared'] === true)
        ->sortByDesc('_updated_time')
        ->values();

    $upcomingGames = $data
        ->filter(fn ($game) => $game['_is_declared'] === false)
        ->filter(fn ($game) => !empty($game['_game_time']) && $game['_game_time'] >= $now->timestamp)
        ->sortBy('_game_time')
        ->values();

    $finalGames = $declaredGames
        ->concat($upcomingGames)
        ->take($limit)
        ->values()
        ->map(function ($game) {
            unset(
                $game['_is_declared'],
                $game['_is_live_declared'],
                $game['_updated_time'],
                $game['_game_time']
            );

            return $game;
        });

    return response()->json([
        'success' => true,
        'date' => $today,
        'time' => $now->format('H:i:s'),
        'games' => $finalGames,
    ]);
}



public function homeLiveResults333(Request $request)
{
    $timezone = 'Asia/Kolkata';

    $now = Carbon::now($timezone);
    $today = $now->format('Y-m-d');
    $yesterday = $now->copy()->subDay()->format('Y-m-d');

    $limit = max(1, min((int) $request->get('limit', 4), 20));

    $games = Game::with([
            'results' => function ($q) use ($today, $yesterday) {
                $q->whereBetween('result_date', [$yesterday, $today])
                    ->where('status', 'declared')
                    ->whereNotNull('result')
                    ->where('result', '!=', '')
                    ->latest('updated_at');
            }
        ])
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    $data = $games->map(function ($game) use ($now, $timezone) {

        $todayResult = $game->results->first();

        $isDeclared = $todayResult
            && $todayResult->status === 'declared'
            && filled($todayResult->result);

        $showMinutes = $todayResult && filled($todayResult->show_minutes)
            ? (int) $todayResult->show_minutes
            : 10;

        $updatedAt = $todayResult?->updated_at
            ? Carbon::parse($todayResult->updated_at, $timezone)->timezone($timezone)
            : null;

        $isLive = false;

        if ($isDeclared) {
            if ($showMinutes <= 0) {
                $isLive = true;
            } elseif ($updatedAt) {
                $isLive = $now->lessThanOrEqualTo(
                    $updatedAt->copy()->addMinutes($showMinutes)
                );
            }
        }

        $gameDateTime = null;

        if (filled($game->result_time)) {
            try {
                $gameDateTime = Carbon::parse(
                    $now->format('Y-m-d') . ' ' . trim($game->result_time),
                    $timezone
                );
            } catch (\Throwable $e) {
                $gameDateTime = null;
            }
        }

        return [
            'id' => $game->id,
            'name' => $game->name,
            'slug' => $game->slug,
            'result_time' => $game->result_time,
            'sort_order' => $game->sort_order,

            '_is_declared' => $isDeclared,
            '_is_live_declared' => $isLive,
            '_updated_time' => $updatedAt?->timestamp,
            '_game_time' => $gameDateTime?->timestamp,

            'result' => [
                'id' => $todayResult?->id,
                'result_date' => $todayResult?->result_date
                    ? Carbon::parse($todayResult->result_date, $timezone)->format('Y-m-d')
                    : null,
                'result' => $isLive && $todayResult ? $todayResult->result : null,
                'status' => $isLive ? 'declared' : 'waiting',
                'show_minutes' => $showMinutes,
                'updated_at' => $updatedAt?->format('Y-m-d H:i:s'),
                'is_live' => $isLive,
            ],
        ];
    });

    $declaredGames = $data
        ->filter(fn ($game) => $game['_is_live_declared'] === true)
        ->sortByDesc('_updated_time')
        ->values();

    $upcomingGames = $data
        ->filter(fn ($game) => $game['_is_live_declared'] === false)
        ->filter(fn ($game) => !empty($game['_game_time']) && $game['_game_time'] >= $now->timestamp)
        ->sortBy('_game_time')
        ->values();

    $finalGames = $declaredGames
        ->concat($upcomingGames)
        ->take($limit)
        ->values()
        ->map(function ($game) {
            unset(
                $game['_is_declared'],
                $game['_is_live_declared'],
                $game['_updated_time'],
                $game['_game_time']
            );

            return $game;
        });

    return response()->json([
        'success' => true,
        'date' => $today,
        'time' => $now->format('H:i:s'),
        'games' => $finalGames,
    ]);
}





public function homeLiveResults(Request $request)
{
    $timezone = 'Asia/Kolkata';

    $now = Carbon::now($timezone);
    $today = $now->format('Y-m-d');
    $yesterday = $now->copy()->subDay()->format('Y-m-d');

    $limit = max(1, min((int) $request->get('limit', 4), 20));

    $games = Game::with([
            'results' => function ($q) use ($today, $yesterday) {
                $q->whereBetween('result_date', [$yesterday, $today])
                    ->where('status', 'declared')
                    ->whereNotNull('result')
                    ->where('result', '!=', '')
                    ->latest('updated_at');
            }
        ])
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    $data = $games->map(function ($game) use ($now, $timezone) {

        $todayResult = $game->results->first();

        $isDeclared = $todayResult
            && $todayResult->status === 'declared'
            && filled($todayResult->result);

        $showMinutes = $todayResult && filled($todayResult->show_minutes)
            ? (int) $todayResult->show_minutes
            : 10;

        $updatedAt = $todayResult?->updated_at
            ? Carbon::parse($todayResult->updated_at)->timezone($timezone)
            : null;

        $isLive = false;

        if ($isDeclared) {
            if ($showMinutes <= 0) {
                $isLive = true;
            } elseif ($updatedAt) {
                $isLive = $now->lessThanOrEqualTo(
                    $updatedAt->copy()->addMinutes($showMinutes)
                );
            }
        }

        $gameDateTime = null;
        $waitStartTime = null;
        $isUpcomingWait = false;

        if (filled($game->result_time)) {
            try {
                $gameDateTime = Carbon::parse(
                    $now->format('Y-m-d') . ' ' . trim($game->result_time),
                    $timezone
                );

                $waitStartTime = $gameDateTime->copy()->subMinutes(5);

                // Result time se sirf 5 minute pehle WAIT show hoga
                $isUpcomingWait = !$isLive
                    && $now->betweenIncluded($waitStartTime, $gameDateTime);

            } catch (\Throwable $e) {
                $gameDateTime = null;
                $waitStartTime = null;
                $isUpcomingWait = false;
            }
        }

        return [
            'id' => $game->id,
            'name' => $game->name,
            'slug' => $game->slug,
            'result_time' => $game->result_time,
            'sort_order' => $game->sort_order,

            '_is_declared' => $isDeclared,
            '_is_live_declared' => $isLive,
            '_is_upcoming_wait' => $isUpcomingWait,
            '_updated_time' => $updatedAt?->timestamp,
            '_game_time' => $gameDateTime?->timestamp,

            'result' => [
                'id' => $todayResult?->id,
                'result_date' => $todayResult?->result_date
                    ? Carbon::parse($todayResult->result_date, $timezone)->format('Y-m-d')
                    : null,

                'result' => $isLive && $todayResult ? $todayResult->result : null,
                'status' => $isLive ? 'declared' : 'waiting',
                'show_minutes' => $showMinutes,
                'updated_at' => $updatedAt?->format('Y-m-d H:i:s'),
                'is_live' => $isLive,
            ],
        ];
    });

    /*
    |--------------------------------------------------------------------------
    | Declared Games
    |--------------------------------------------------------------------------
    | Result aaye to top me dikhega, show_minutes tak.
    */
    $declaredGames = $data
        ->filter(fn ($game) => $game['_is_live_declared'] === true)
        ->sortByDesc('_updated_time')
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Upcoming Wait Games
    |--------------------------------------------------------------------------
    | Sirf result_time se 5 minute pehle WAIT show hoga.
    */
    $upcomingGames = $data
        ->filter(fn ($game) => $game['_is_live_declared'] === false)
        ->filter(fn ($game) => $game['_is_upcoming_wait'] === true)
        ->sortBy('_game_time')
        ->values();

    $finalGames = $declaredGames
        ->concat($upcomingGames)
        ->take($limit)
        ->values()
        ->map(function ($game) {
            unset(
                $game['_is_declared'],
                $game['_is_live_declared'],
                $game['_is_upcoming_wait'],
                $game['_updated_time'],
                $game['_game_time']
            );

            return $game;
        });

    return response()->json([
        'success' => true,
        'date' => $today,
        'time' => $now->format('H:i:s'),
        'games' => $finalGames,
    ]);
}

}