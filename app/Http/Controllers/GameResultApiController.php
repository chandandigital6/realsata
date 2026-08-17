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




public function live()
{
    $timezone = 'Asia/Kolkata';

    $now = Carbon::now($timezone);
    $today = $now->format('Y-m-d');

    /*
    |--------------------------------------------------------------------------
    | Active Games With TODAY Declared Results Only
    |--------------------------------------------------------------------------
    |
    | Sirf aaj ke declared results load honge.
    | Yesterday ka result live section me kabhi use nahi hoga.
    |
    */
    $games = Game::with([
            'results' => function ($q) use ($today) {
                $q->whereDate('result_date', $today)
                    ->where('status', 'declared')
                    ->whereNotNull('result')
                    ->where('result', '!=', '')
                    ->latest('updated_at');
            }
        ])
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Prepare Game Data
    |--------------------------------------------------------------------------
    */
    $data = $games->map(function ($game) use ($now, $timezone, $today) {

        /*
        |--------------------------------------------------------------------------
        | Today Latest Declared Result
        |--------------------------------------------------------------------------
        */
        $todayResult = $game->results->first();

        $hasTodayDeclared = $todayResult
            && $todayResult->status === 'declared'
            && filled($todayResult->result);

        /*
        |--------------------------------------------------------------------------
        | Show Minutes
        |--------------------------------------------------------------------------
        */
        $showMinutes = $todayResult && filled($todayResult->show_minutes)
            ? (int) $todayResult->show_minutes
            : 10;

        /*
        |--------------------------------------------------------------------------
        | Updated At
        |--------------------------------------------------------------------------
        */
        $updatedAt = $todayResult?->updated_at
            ? Carbon::parse($todayResult->updated_at)
                ->timezone($timezone)
            : null;

        /*
        |--------------------------------------------------------------------------
        | Is Live Declared
        |--------------------------------------------------------------------------
        |
        | Result declare hone ke baad show_minutes tak top me show hoga.
        |
        | show_minutes <= 0:
        | result ko live maana jayega.
        |
        */
        $isLive = false;

        if ($hasTodayDeclared) {

            if ($showMinutes <= 0) {

                $isLive = true;

            } elseif ($updatedAt) {

                $isLive = $now->lessThanOrEqualTo(
                    $updatedAt->copy()->addMinutes($showMinutes)
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Game Time Calculation
        |--------------------------------------------------------------------------
        */
        $gameDateTime = null;

        $isWaitingWindow = false;
        $isFutureGame = false;

        if (filled($game->result_time)) {

            try {

                $gameDateTime = Carbon::parse(
                    $today . ' ' . trim($game->result_time),
                    $timezone
                );

                /*
                |--------------------------------------------------------------------------
                | Waiting Start
                |--------------------------------------------------------------------------
                |
                | Result time se 5 minute pehle waiting start hogi.
                |
                | Example:
                |
                | CHAMELI BAZAR = 12:30 PM
                |
                | 12:25 PM se waiting.
                |
                | Jab tak result declare nahi hota waiting continue rahegi.
                |
                */
                $waitingStartTime = $gameDateTime
                    ->copy()
                    ->subMinutes(5);

                /*
                |--------------------------------------------------------------------------
                | Waiting Game
                |--------------------------------------------------------------------------
                */
                $isWaitingWindow =
                    !$hasTodayDeclared
                    && $now->greaterThanOrEqualTo($waitingStartTime);

                /*
                |--------------------------------------------------------------------------
                | Future Game
                |--------------------------------------------------------------------------
                |
                | Waiting start hone se pehle future game.
                |
                */
                $isFutureGame =
                    !$hasTodayDeclared
                    && $now->lessThan($waitingStartTime);

            } catch (\Throwable $e) {

                $gameDateTime = null;
                $isWaitingWindow = false;
                $isFutureGame = false;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Return Game
        |--------------------------------------------------------------------------
        */
        return [

            'id' => $game->id,

            'name' => $game->name,

            'slug' => $game->slug,

            'result_time' => $game->result_time,

            'sort_order' => $game->sort_order,

            'is_active' => (bool) $game->is_active,

            /*
            |--------------------------------------------------------------------------
            | Internal Helper Fields
            |--------------------------------------------------------------------------
            */
            '_has_today_declared' => $hasTodayDeclared,

            '_is_live_declared' => $isLive,

            '_updated_timestamp' => $updatedAt?->timestamp,

            '_game_timestamp' => $gameDateTime?->timestamp,

            '_is_waiting_window' => $isWaitingWindow,

            '_is_future_game' => $isFutureGame,

            /*
            |--------------------------------------------------------------------------
            | Result
            |--------------------------------------------------------------------------
            */
            'result' => [

                'id' => $todayResult?->id,

                'result_date' => $todayResult?->result_date
                    ? Carbon::parse(
                        $todayResult->result_date,
                        $timezone
                    )->format('Y-m-d')
                    : null,

                /*
                | Result sirf live duration me expose hoga.
                */
                'result' => $isLive && $todayResult
                    ? $todayResult->result
                    : null,

                /*
                | Live hai to declared,
                | warna waiting.
                */
                'status' => $isLive
                    ? 'declared'
                    : 'waiting',

                'show_minutes' => $showMinutes,

                'updated_at' => $updatedAt?->format(
                    'Y-m-d H:i:s'
                ),

                'is_live' => $isLive,
            ],
        ];
    });

    /*
    |--------------------------------------------------------------------------
    | Declared Live Games
    |--------------------------------------------------------------------------
    |
    | Recently declared games sabse pehle.
    |
    */
    $declaredGames = $data
        ->filter(function ($game) {
            return $game['_is_live_declared'] === true;
        })
        ->sortByDesc('_updated_timestamp')
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Waiting Games
    |--------------------------------------------------------------------------
    |
    | Result time ke 5 minute pehle se lekar
    | result declare hone tak waiting me rahega.
    |
    */
    $waitingGames = $data
        ->filter(function ($game) {

            return $game['_is_live_declared'] === false
                && $game['_has_today_declared'] === false
                && $game['_is_waiting_window'] === true;
        })
        ->sortBy('_game_timestamp')
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Future Games
    |--------------------------------------------------------------------------
    |
    | Jinka waiting time abhi start nahi hua.
    |
    */
    $futureGames = $data
        ->filter(function ($game) {

            return $game['_is_live_declared'] === false
                && $game['_has_today_declared'] === false
                && $game['_is_future_game'] === true;
        })
        ->sortBy('_game_timestamp')
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Final Live Games
    |--------------------------------------------------------------------------
    |
    | Priority:
    |
    | 1. Declared live result
    | 2. Waiting games
    | 3. Future games
    |
    */
    $finalGames = $declaredGames
        ->concat($waitingGames)
        ->concat($futureGames)
        ->unique('id')
        ->take(4)
        ->values()
        ->map(function ($game) {

            /*
            |--------------------------------------------------------------------------
            | Remove Internal Fields
            |--------------------------------------------------------------------------
            */
            unset(
                $game['_has_today_declared'],
                $game['_is_live_declared'],
                $game['_updated_timestamp'],
                $game['_game_timestamp'],
                $game['_is_waiting_window'],
                $game['_is_future_game']
            );

            return $game;
        });

    /*
    |--------------------------------------------------------------------------
    | Response
    |--------------------------------------------------------------------------
    */
    return response()->json([
        'success' => true,
        'date' => $today,
        'time' => $now->format('H:i:s'),
        'games' => $finalGames,
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




public function homeLiveResults(Request $request)
{
    $timezone = 'Asia/Kolkata';

    $now = Carbon::now($timezone);
    $today = $now->format('Y-m-d');

    $limit = max(1, min((int) $request->get('limit', 4), 20));

    /*
    |--------------------------------------------------------------------------
    | Active Games With TODAY Results Only
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Live API me sirf aaj ke results load honge.
    | Yesterday ka result live result ke roop me kabhi use nahi hoga.
    |
    */
    $games = Game::with([
            'results' => function ($q) use ($today) {
                $q->whereDate('result_date', $today)
                    ->where('status', 'declared')
                    ->whereNotNull('result')
                    ->where('result', '!=', '')
                    ->latest('updated_at');
            }
        ])
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Prepare Games Data
    |--------------------------------------------------------------------------
    */
    $data = $games->map(function ($game) use ($now, $timezone, $today) {

        /*
        |--------------------------------------------------------------------------
        | Today's Declared Result
        |--------------------------------------------------------------------------
        |
        | Relation me already sirf today's records hain.
        | Latest declared result pick karenge.
        |
        */
        $todayResult = $game->results->first();

        $hasTodayDeclared = $todayResult
            && $todayResult->status === 'declared'
            && filled($todayResult->result);

        /*
        |--------------------------------------------------------------------------
        | Show Minutes
        |--------------------------------------------------------------------------
        */
        $showMinutes = $todayResult && filled($todayResult->show_minutes)
            ? (int) $todayResult->show_minutes
            : 10;

        /*
        |--------------------------------------------------------------------------
        | Updated At
        |--------------------------------------------------------------------------
        */
        $updatedAt = $todayResult?->updated_at
            ? Carbon::parse($todayResult->updated_at)
                ->timezone($timezone)
            : null;

        /*
        |--------------------------------------------------------------------------
        | Declared Result Live Duration
        |--------------------------------------------------------------------------
        |
        | Result declare hone ke baad show_minutes tak live top section me rahega.
        |
        */
        $isLive = false;

        if ($hasTodayDeclared) {

            if ($showMinutes <= 0) {

                $isLive = true;

            } elseif ($updatedAt) {

                $isLive = $now->lessThanOrEqualTo(
                    $updatedAt->copy()->addMinutes($showMinutes)
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Game Time
        |--------------------------------------------------------------------------
        */
        $gameDateTime = null;

        $isWaitingWindow = false;
        $isFutureGame = false;

        if (filled($game->result_time)) {

            try {

                $gameDateTime = Carbon::parse(
                    $today . ' ' . trim($game->result_time),
                    $timezone
                );

                /*
                |--------------------------------------------------------------------------
                | Waiting Starts 5 Minutes Before Result Time
                |--------------------------------------------------------------------------
                |
                | Example:
                | CHAMELI BAZAR = 12:30 PM
                |
                | 12:25 PM se waiting start.
                |
                | IMPORTANT:
                | Result jab tak declare nahi hoga waiting continue rahegi.
                | +45 minutes wali limit remove kar di hai.
                |
                */
                $waitingStartTime = $gameDateTime
                    ->copy()
                    ->subMinutes(5);

                $isWaitingWindow =
                    !$hasTodayDeclared
                    && $now->greaterThanOrEqualTo($waitingStartTime);

                /*
                |--------------------------------------------------------------------------
                | Future Game
                |--------------------------------------------------------------------------
                |
                | Waiting start hone se pehle tak future game rahega.
                |
                */
                $isFutureGame =
                    !$hasTodayDeclared
                    && $now->lessThan($waitingStartTime);

            } catch (\Throwable $e) {

                $gameDateTime = null;
                $isWaitingWindow = false;
                $isFutureGame = false;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | API Result
        |--------------------------------------------------------------------------
        */
        return [

            'id' => $game->id,

            'name' => $game->name,

            'slug' => $game->slug,

            'result_time' => $game->result_time,

            'sort_order' => $game->sort_order,

            /*
            |--------------------------------------------------------------------------
            | Internal Fields
            |--------------------------------------------------------------------------
            */
            '_has_today_declared' => $hasTodayDeclared,

            '_is_live_declared' => $isLive,

            '_updated_time' => $updatedAt?->timestamp,

            '_game_time' => $gameDateTime?->timestamp,

            '_is_waiting_window' => $isWaitingWindow,

            '_is_future_game' => $isFutureGame,

            /*
            |--------------------------------------------------------------------------
            | Public Result Object
            |--------------------------------------------------------------------------
            */
            'result' => [

                'id' => $todayResult?->id,

                'result_date' => $todayResult?->result_date
                    ? Carbon::parse(
                        $todayResult->result_date,
                        $timezone
                    )->format('Y-m-d')
                    : null,

                /*
                | Result sirf tab bhejna hai jab wo currently live ho.
                */
                'result' => $isLive && $todayResult
                    ? $todayResult->result
                    : null,

                /*
                | Live result => declared
                | Otherwise => waiting
                */
                'status' => $isLive
                    ? 'declared'
                    : 'waiting',

                'show_minutes' => $showMinutes,

                'updated_at' => $updatedAt?->format(
                    'Y-m-d H:i:s'
                ),

                'is_live' => $isLive,
            ],
        ];
    });

    /*
    |--------------------------------------------------------------------------
    | Declared Live Games
    |--------------------------------------------------------------------------
    |
    | Recently declared result sabse pehle.
    |
    */
    $declaredGames = $data
        ->filter(function ($game) {
            return $game['_is_live_declared'] === true;
        })
        ->sortByDesc('_updated_time')
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Waiting Games
    |--------------------------------------------------------------------------
    |
    | Result time ke 5 minute pehle se lekar
    | result declare hone tak waiting me rahega.
    |
    */
    $waitingGames = $data
        ->filter(function ($game) {

            return $game['_is_live_declared'] === false
                && $game['_has_today_declared'] === false
                && $game['_is_waiting_window'] === true;
        })
        ->sortBy('_game_time')
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Future Games
    |--------------------------------------------------------------------------
    |
    | Jinka waiting time abhi start nahi hua.
    |
    */
    $futureGames = $data
        ->filter(function ($game) {

            return $game['_is_live_declared'] === false
                && $game['_has_today_declared'] === false
                && $game['_is_future_game'] === true;
        })
        ->sortBy('_game_time')
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Final Games
    |--------------------------------------------------------------------------
    |
    | Priority:
    |
    | 1. Live Declared
    | 2. Waiting
    | 3. Future
    |
    */
    $finalGames = $declaredGames
        ->concat($waitingGames)
        ->concat($futureGames)
        ->unique('id')
        ->take($limit)
        ->values()
        ->map(function ($game) {

            /*
            |--------------------------------------------------------------------------
            | Remove Internal Helper Fields
            |--------------------------------------------------------------------------
            */
            unset(
                $game['_has_today_declared'],
                $game['_is_live_declared'],
                $game['_updated_time'],
                $game['_game_time'],
                $game['_is_waiting_window'],
                $game['_is_future_game']
            );

            return $game;
        });

    /*
    |--------------------------------------------------------------------------
    | Response
    |--------------------------------------------------------------------------
    */
    return response()->json([
        'success' => true,
        'date' => $today,
        'time' => $now->format('H:i:s'),
        'games' => $finalGames,
    ]);
}

public function homeLiveResultsolldd(Request $request)
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

    $data = $games->map(function ($game) use ($now, $timezone, $today) {

        /*
        |--------------------------------------------------------------------------
        | Today Result Check
        |--------------------------------------------------------------------------
        | Jis game ka aaj result aa gaya hai, usko waiting/future me dobara nahi dikhana.
        */
        $todayDeclaredResult = $game->results->first(function ($result) use ($today, $timezone) {
            return Carbon::parse($result->result_date, $timezone)->format('Y-m-d') === $today
                && $result->status === 'declared'
                && filled($result->result);
        });

        /*
        |--------------------------------------------------------------------------
        | Live Result Pick
        |--------------------------------------------------------------------------
        | Live box ke liye latest declared result today/yesterday me se pick hoga.
        */
        $todayResult = $game->results->first();

        $isDeclared = $todayResult
            && $todayResult->status === 'declared'
            && filled($todayResult->result);

        /*
        |--------------------------------------------------------------------------
        | Has Today Declared
        |--------------------------------------------------------------------------
        | Ye important hai.
        | Aaj ka result aa gaya to game waiting/future me nahi jayega.
        */
        $hasTodayDeclared = $todayDeclaredResult
            && $todayDeclaredResult->status === 'declared'
            && filled($todayDeclaredResult->result);

        $showMinutes = $todayResult && filled($todayResult->show_minutes)
            ? (int) $todayResult->show_minutes
            : 10;

        $updatedAt = $todayResult?->updated_at
            ? Carbon::parse($todayResult->updated_at)->timezone($timezone)
            : null;

        /*
        |--------------------------------------------------------------------------
        | Result Live Check
        |--------------------------------------------------------------------------
        */
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

        /*
        |--------------------------------------------------------------------------
        | Game Time Calculation
        |--------------------------------------------------------------------------
        */
        $gameDateTime = null;
        $isWaitingWindow = false;
        $isFutureGame = false;

        if (filled($game->result_time)) {
            try {
                $gameDateTime = Carbon::parse(
                    $today . ' ' . trim($game->result_time),
                    $timezone
                );

                $showStartTime = $gameDateTime->copy()->subMinutes(5);
                $showEndTime = $gameDateTime->copy()->addMinutes(45);

                /*
                |--------------------------------------------------------------------------
                | Waiting Window
                |--------------------------------------------------------------------------
                | Lekin agar aaj ka result aa chuka hai to waiting false rahega.
                */
                $isWaitingWindow = !$hasTodayDeclared
                    && $now->between($showStartTime, $showEndTime);

                /*
                |--------------------------------------------------------------------------
                | Future Game
                |--------------------------------------------------------------------------
                | Lekin agar aaj ka result aa chuka hai to future false rahega.
                */
                $isFutureGame = !$hasTodayDeclared
                    && $gameDateTime->greaterThan($now);

            } catch (\Throwable $e) {
                $gameDateTime = null;
                $isWaitingWindow = false;
                $isFutureGame = false;
            }
        }

        return [
            'id' => $game->id,
            'name' => $game->name,
            'slug' => $game->slug,
            'result_time' => $game->result_time,
            'sort_order' => $game->sort_order,

            '_has_today_declared' => $hasTodayDeclared,
            '_is_declared' => $isDeclared,
            '_is_live_declared' => $isLive,
            '_updated_time' => $updatedAt?->timestamp,
            '_game_time' => $gameDateTime?->timestamp,
            '_is_waiting_window' => $isWaitingWindow,
            '_is_future_game' => $isFutureGame,

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
    | Declared Live Games
    |--------------------------------------------------------------------------
    | Jiska result live duration me hai wo top me dikhega.
    */
    $declaredGames = $data
        ->filter(fn ($game) => $game['_is_live_declared'] === true)
        ->sortByDesc('_updated_time')
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Waiting Window Games
    |--------------------------------------------------------------------------
    | Aaj result aa chuka hai to yaha nahi aayega.
    */
    $waitingGames = $data
        ->filter(fn ($game) => $game['_is_live_declared'] === false)
        ->filter(fn ($game) => $game['_has_today_declared'] === false)
        ->filter(fn ($game) => $game['_is_waiting_window'] === true)
        ->sortBy('_game_time')
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Future Upcoming Games
    |--------------------------------------------------------------------------
    | Aaj result aa chuka hai to yaha nahi aayega.
    */
    $futureGames = $data
        ->filter(fn ($game) => $game['_is_live_declared'] === false)
        ->filter(fn ($game) => $game['_has_today_declared'] === false)
        ->filter(fn ($game) => $game['_is_future_game'] === true)
        ->sortBy('_game_time')
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Final Games
    |--------------------------------------------------------------------------
    */
    $finalGames = $declaredGames
        ->concat($waitingGames)
        ->concat($futureGames)
        ->unique('id')
        ->take($limit)
        ->values()
        ->map(function ($game) {
            unset(
                $game['_has_today_declared'],
                $game['_is_declared'],
                $game['_is_live_declared'],
                $game['_updated_time'],
                $game['_game_time'],
                $game['_is_waiting_window'],
                $game['_is_future_game']
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
