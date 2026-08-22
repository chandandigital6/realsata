<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\ContentBlock;
use App\Models\Game;
use App\Models\GameResult;
use App\Models\SeoPage;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Cache;

class FrontController extends Controller
{




  public function home()
    {
        $timezone = 'Asia/Kolkata';

        $now = now($timezone);
        $today = $now->toDateString();
        $yesterday = $now->copy()->subDay()->toDateString();

        /*
    |--------------------------------------------------------------------------
    | Active Games
    |--------------------------------------------------------------------------
    */
        $games = Game::query()
            ->select([
                'id',
                'name',
                'slug',
                'result_time',
                'sort_order',
                'is_active',
            ])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $gameIds = $games->pluck('id');

        /* Late-night game ki working date decide karne ke liye recent results. */
        $recentDeclaredResults = GameResult::query()
            ->select(['id', 'game_id', 'result_date', 'result', 'status', 'show_minutes', 'updated_at'])
            ->whereIn('game_id', $gameIds)
            ->whereBetween('result_date', [$yesterday, $today])
            ->where('status', 'declared')
            ->whereNotNull('result')
            ->where('result', '!=', '')
            ->orderByDesc('updated_at')
            ->get()
            ->groupBy('game_id');

        /*
        | Late-night cycle rule:
        | - Koi fixed 12:10/01:00 cutoff nahi hai.
        | - Yesterday result nahi aaya to yesterday game waiting me rahega.
        | - Declare hone ke baad updated_at + show_minutes tak wahi result rahega.
        | - Expire hone ke baad current-day cycle start hoga.
        */
        $resultDateByGame = $games->mapWithKeys(function ($game) use ($now, $today, $yesterday, $timezone, $recentDeclaredResults) {
            if (empty($game->result_time)) {
                return [$game->id => $today];
            }

            try {
                $todayGameTime = Carbon::parse($today . ' ' . trim($game->result_time), $timezone);

                if ((int) $todayGameTime->format('H') < 21) {
                    return [$game->id => $today];
                }

                $previousResult = ($recentDeclaredResults->get($game->id, collect()))
                    ->first(fn ($result) => Carbon::parse($result->result_date)->toDateString() === $yesterday);

                if ($previousResult && $previousResult->updated_at) {
                    $showMinutes = (int) ($previousResult->show_minutes ?? 0);

                    if ($showMinutes <= 0) {
                        return [$game->id => $yesterday];
                    }

                    $hideAfter = Carbon::parse($previousResult->updated_at)
                        ->timezone($timezone)
                        ->addMinutes($showMinutes);

                    if ($now->lessThanOrEqualTo($hideAfter)) {
                        return [$game->id => $yesterday];
                    }

                    return [$game->id => $today];
                }

                $todayWaitingStart = $todayGameTime->copy()->subMinutes(5);

                return [$game->id => $now->lt($todayWaitingStart) ? $yesterday : $today];
            } catch (\Throwable $e) {
                return [$game->id => $today];
            }
        });

        $gameSections = $games->chunk(18);
        $chartGameSections = $games->chunk(18);

        /*
    |--------------------------------------------------------------------------
    | Today Table Results
    |--------------------------------------------------------------------------
    |
    | Sirf aaj ke declared results.
    |
    */
        $todayTableResults = GameResult::query()
            ->select([
                'id',
                'game_id',
                'result_date',
                'result',
                'status',
                'show_minutes',
                'updated_at',
            ])
            ->whereIn('game_id', $gameIds)
            ->whereBetween('result_date', [$yesterday, $today])
            ->where('status', 'declared')
            ->whereNotNull('result')
            ->where('result', '!=', '')
            ->orderByDesc('updated_at')
            ->get()
            ->filter(function ($result) use ($resultDateByGame, $games, $yesterday, $now, $timezone) {
                $resultDate = Carbon::parse($result->result_date)->toDateString();

                if ($resultDate === ($resultDateByGame[$result->game_id] ?? null)) {
                    return true;
                }

                $game = $games->firstWhere('id', $result->game_id);
                $showMinutes = (int) ($result->show_minutes ?? 0);

                if (!$game || empty($game->result_time) || $showMinutes <= 0 || $resultDate !== $yesterday) {
                    return false;
                }

                $gameHour = (int) Carbon::parse($now->toDateString() . ' ' . trim($game->result_time), $timezone)->format('H');
                $hideAfter = Carbon::parse($result->updated_at)->timezone($timezone)->addMinutes($showMinutes);

                return $gameHour >= 21 && $now->lessThanOrEqualTo($hideAfter);
            })
            ->unique('game_id')
            ->keyBy('game_id');

        /*
    |--------------------------------------------------------------------------
    | Today Live Declared Results
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Pehle yesterday + today dono results aa rahe the.
    | Is wajah se new game me purana result jaise 58 dikh raha tha.
    |
    | Ab sirf aaj ka result live section me aayega.
    |
    */
        $todayResults = GameResult::query()
            ->select([
                'id',
                'game_id',
                'result_date',
                'result',
                'status',
                'show_minutes',
                'updated_at',
            ])
            ->whereIn('game_id', $gameIds)
            ->whereBetween('result_date', [$yesterday, $today])
            ->where('status', 'declared')
            ->whereNotNull('result')
            ->where('result', '!=', '')
            ->orderByDesc('updated_at')
            ->get()
            ->filter(function ($result) use ($resultDateByGame, $games, $yesterday, $now, $timezone) {
                $resultDate = Carbon::parse($result->result_date)->toDateString();

                if ($resultDate === ($resultDateByGame[$result->game_id] ?? null)) {
                    return true;
                }

                $game = $games->firstWhere('id', $result->game_id);
                $showMinutes = (int) ($result->show_minutes ?? 0);

                if (!$game || empty($game->result_time) || $showMinutes <= 0 || $resultDate !== $yesterday) {
                    return false;
                }

                $gameHour = (int) Carbon::parse($now->toDateString() . ' ' . trim($game->result_time), $timezone)->format('H');
                $hideAfter = Carbon::parse($result->updated_at)->timezone($timezone)->addMinutes($showMinutes);

                return $gameHour >= 21 && $now->lessThanOrEqualTo($hideAfter);
            })
            ->filter(function ($result) use ($timezone) {

                $showMinutes = (int) ($result->show_minutes ?? 0);

                /*
            |--------------------------------------------------------------------------
            | show_minutes <= 0
            |--------------------------------------------------------------------------
            |
            | Agar show_minutes set nahi hai ya 0 hai,
            | result ko live result me allow karenge.
            |
            */
                if ($showMinutes <= 0) {
                    return true;
                }

                $updatedAt = Carbon::parse($result->updated_at)
                    ->timezone($timezone);

                $hideAfter = $updatedAt->copy()->addMinutes($showMinutes);

                return now($timezone)->lessThanOrEqualTo($hideAfter);
            })
            ->unique('game_id')
            ->keyBy('game_id');

        /*
    |--------------------------------------------------------------------------
    | Yesterday Results
    |--------------------------------------------------------------------------
    */
        $yesterdayResults = GameResult::query()
            ->select([
                'id',
                'game_id',
                'result_date',
                'result',
                'status',
                'updated_at',
            ])
            ->whereIn('game_id', $gameIds)
            ->whereDate('result_date', $yesterday)
            ->where('status', 'declared')
            ->whereNotNull('result')
            ->where('result', '!=', '')
            ->orderByDesc('updated_at')
            ->get()
            ->unique('game_id')
            ->keyBy('game_id');

        /*
    |--------------------------------------------------------------------------
    | Declared Live Games
    |--------------------------------------------------------------------------
    |
    | Jin games ka aaj result declared hai,
    | unko sabse pehle live list me dikhayenge.
    |
    */
        $declaredGames = $games
            ->filter(function ($game) use ($todayResults) {

                return isset($todayResults[$game->id])
                    && filled($todayResults[$game->id]->result);
            })
            ->sortByDesc(function ($game) use ($todayResults, $timezone) {

                return Carbon::parse(
                    $todayResults[$game->id]->updated_at
                )
                    ->timezone($timezone)
                    ->timestamp;
            });

        /*
    |--------------------------------------------------------------------------
    | Waiting Games
    |--------------------------------------------------------------------------
    |
    | Rule:
    |
    | 1. Aaj result declared nahi hona chahiye.
    | 2. result_time available hona chahiye.
    | 3. Result time se 5 minute pehle waiting start hogi.
    | 4. Result declare hone tak waiting rahegi.
    |
    | Example:
    | CHAMELI BAZAR = 12:30 PM
    |
    | 12:25 PM se waiting show hogi.
    | Result declare nahi hua to 1 PM, 2 PM etc. tak bhi waiting rahegi.
    |
    */
        $waitingGames = $games
            ->filter(function ($game) use ($todayTableResults) {

                /*
            | Aaj ka result already declared hai.
            */
                if (isset($todayTableResults[$game->id])) {
                    return false;
                }

                /*
            | Result time missing hai.
            */
                if (empty($game->result_time)) {
                    return false;
                }

                return true;
            })
            ->filter(function ($game) use ($now, $timezone, $resultDateByGame) {

                try {

                    $workingDate = $resultDateByGame[$game->id] ?? $now->toDateString();
                    $gameTime = Carbon::parse(
                        $workingDate . ' ' . trim($game->result_time),
                        $timezone
                    );

                    /*
                | Result time se 5 minute pehle waiting start.
                */
                    $waitingStart = $gameTime->copy()->subMinutes(5);

                    return $now->greaterThanOrEqualTo($waitingStart);
                } catch (\Throwable $e) {

                    return false;
                }
            })
            ->sortBy(function ($game) use ($now, $timezone, $resultDateByGame) {

                try {

                    return Carbon::parse(
                        ($resultDateByGame[$game->id] ?? $now->toDateString()) . ' ' . trim($game->result_time),
                        $timezone
                    )->timestamp;
                } catch (\Throwable $e) {

                    return PHP_INT_MAX;
                }
            });

        /*
    |--------------------------------------------------------------------------
    | Future Upcoming Games
    |--------------------------------------------------------------------------
    |
    | Jin games ka aaj result nahi hai aur result time future me hai.
    |
    */
        $futureGames = $games
            ->filter(function ($game) use ($todayTableResults, $now, $timezone, $resultDateByGame) {

                /*
            | Result already declared.
            */
                if (isset($todayTableResults[$game->id])) {
                    return false;
                }

                /*
            | Result time nahi hai.
            */
                if (empty($game->result_time)) {
                    return false;
                }

                try {

                    $workingDate = $resultDateByGame[$game->id] ?? $now->toDateString();
                    $gameTime = Carbon::parse(
                        $workingDate . ' ' . trim($game->result_time),
                        $timezone
                    );

                    /*
                | Waiting 5 minute pehle start hoti hai.
                | Isliye exact future games me 5 min window exclude karenge.
                */
                    $waitingStart = $gameTime->copy()->subMinutes(5);

                    return $now->lessThan($waitingStart);
                } catch (\Throwable $e) {

                    return false;
                }
            })
            ->sortBy(function ($game) use ($now, $timezone, $resultDateByGame) {

                try {

                    return Carbon::parse(
                        ($resultDateByGame[$game->id] ?? $now->toDateString()) . ' ' . trim($game->result_time),
                        $timezone
                    )->timestamp;
                } catch (\Throwable $e) {

                    return PHP_INT_MAX;
                }
            });

        /*
    |--------------------------------------------------------------------------
    | Final Live Games
    |--------------------------------------------------------------------------
    |
    | Priority:
    |
    | 1. Recently declared results
    | 2. Waiting games
    | 3. Upcoming games
    |
    */
        $liveGames = $declaredGames
            ->concat($waitingGames)
            ->concat($futureGames)
            ->unique('id')
            ->take(4);

        /*
    |--------------------------------------------------------------------------
    | Monthly Result Dates
    |--------------------------------------------------------------------------
    */
        $startDate = now($timezone)->startOfMonth();
        $endDate = now($timezone)->endOfMonth();

        $dates = CarbonPeriod::create($startDate, $endDate);

        /*
    |--------------------------------------------------------------------------
    | Monthly Results
    |--------------------------------------------------------------------------
    */
        $monthlyResults = GameResult::query()
            ->select([
                'id',
                'game_id',
                'result_date',
                'result',
                'status',
            ])
            ->whereIn('game_id', $gameIds)
            ->whereBetween('result_date', [
                $startDate->format('Y-m-d'),
                $endDate->format('Y-m-d'),
            ])
            ->where('status', 'declared')
            ->get()
            ->groupBy(function ($result) use ($timezone) {

                return Carbon::parse(
                    $result->result_date,
                    $timezone
                )->format('Y-m-d');
            });

        /*
    |--------------------------------------------------------------------------
    | SEO
    |--------------------------------------------------------------------------
    */
        $seo = SeoPage::query()
            ->where('page_key', 'home')
            ->first();

        /*
    |--------------------------------------------------------------------------
    | Advertisements
    |--------------------------------------------------------------------------
    */
        $allAdvertisements = Advertisement::query()
            ->select([
                'id',
                'title',
                'content',
                'image',
                'link',
                'position',
                'is_active',
                'created_at',
            ])
            ->where('is_active', true)
            ->whereIn('position', [
                'top',
                'middle',
                'bottom',
                'sidebar',
            ])
            ->latest()
            ->get()
            ->groupBy('position');

        $advertisements = $allAdvertisements->get(
            'top',
            collect()
        );

        $topAdvertisements = $advertisements;

        $middleAdvertisement = $allAdvertisements
            ->get('middle', collect())
            ->first();

        $bottomAdvertisement = $allAdvertisements
            ->get('bottom', collect())
            ->first();

        $sidebarAdvertisement = $allAdvertisements
            ->get('sidebar', collect())
            ->first();

        /*
    |--------------------------------------------------------------------------
    | Return Home View
    |--------------------------------------------------------------------------
    */
        return view('front.home.index', compact(
            'games',
            'gameSections',
            'chartGameSections',
            'dates',
            'monthlyResults',
            'seo',
            'advertisements',
            'topAdvertisements',
            'middleAdvertisement',
            'bottomAdvertisement',
            'sidebarAdvertisement',
            'todayResults',
            'todayTableResults',
            'yesterdayResults',
            'today',
            'yesterday',
            'liveGames'
        ));
    }




    public function homeold()
    {
        $timezone = 'Asia/Kolkata';

        $now = now($timezone);
        $today = $now->toDateString();
        $yesterday = $now->copy()->subDay()->toDateString();

        /*
        |--------------------------------------------------------------------------
        | Active Games
        |--------------------------------------------------------------------------
        | Logic same hai. Sirf required columns select kiye.
        */
        $games = Game::query()
            ->select([
                'id',
                'name',
                'slug',
                'result_time',
                'sort_order',
                'is_active',
            ])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $gameIds = $games->pluck('id');

        $gameSections = $games->chunk(18);
        $chartGameSections = $games->chunk(18);

        /*
        |--------------------------------------------------------------------------
        | Today Table Results
        |--------------------------------------------------------------------------
        */
        $todayTableResults = GameResult::query()
            ->select([
                'id',
                'game_id',
                'result_date',
                'result',
                'status',
                'show_minutes',
                'updated_at',
            ])
            ->whereIn('game_id', $gameIds)
            ->whereDate('result_date', $today)
            ->where('status', 'declared')
            ->whereNotNull('result')
            ->where('result', '!=', '')
            ->latest('updated_at')
            ->get()
            ->keyBy('game_id');

        /*
        |--------------------------------------------------------------------------
        | Live Declared Results
        |--------------------------------------------------------------------------
        */
        $todayResults = GameResult::query()
            ->select([
                'id',
                'game_id',
                'result_date',
                'result',
                'status',
                'show_minutes',
                'updated_at',
            ])
            ->whereIn('game_id', $gameIds)
            ->whereBetween('result_date', [
                $yesterday,
                $today,
            ])
            ->where('status', 'declared')
            ->whereNotNull('result')
            ->where('result', '!=', '')
            ->latest('updated_at')
            ->get()
            ->filter(function ($result) use ($timezone) {
                $showMinutes = (int) ($result->show_minutes ?? 0);

                if ($showMinutes <= 0) {
                    return true;
                }

                $updatedAt = \Carbon\Carbon::parse($result->updated_at)
                    ->timezone($timezone);

                return now($timezone)->lessThanOrEqualTo(
                    $updatedAt->copy()->addMinutes($showMinutes)
                );
            })
            ->unique('game_id')
            ->keyBy('game_id');

        /*
        |--------------------------------------------------------------------------
        | Yesterday Results
        |--------------------------------------------------------------------------
        */
        $yesterdayResults = GameResult::query()
            ->select([
                'id',
                'game_id',
                'result_date',
                'result',
                'status',
                'updated_at',
            ])
            ->whereIn('game_id', $gameIds)
            ->whereDate('result_date', $yesterday)
            ->where('status', 'declared')
            ->whereNotNull('result')
            ->where('result', '!=', '')
            ->latest('updated_at')
            ->get()
            ->keyBy('game_id');

        /*
        |--------------------------------------------------------------------------
        | Declared Live Games
        |--------------------------------------------------------------------------
        */
        $declaredGames = $games
            ->filter(function ($game) use ($todayResults) {
                return isset($todayResults[$game->id])
                    && filled($todayResults[$game->id]->result);
            })
            ->sortByDesc(function ($game) use ($todayResults, $timezone) {
                return \Carbon\Carbon::parse($todayResults[$game->id]->updated_at)
                    ->timezone($timezone)
                    ->timestamp;
            });

        /*
        |--------------------------------------------------------------------------
        | Waiting Games
        |--------------------------------------------------------------------------
        */
        $waitingGames = $games
            ->filter(function ($game) use ($todayTableResults) {
                return !isset($todayTableResults[$game->id])
                    && !empty($game->result_time);
            })
            ->filter(function ($game) use ($now, $timezone) {
                try {
                    $gameTime = \Carbon\Carbon::parse(
                        $now->format('Y-m-d') . ' ' . trim($game->result_time),
                        $timezone
                    );

                    $showStart = $gameTime->copy()->subMinutes(5);
                    $showEnd = $gameTime->copy()->addMinutes(45);

                    return $now->between($showStart, $showEnd);
                } catch (\Throwable $e) {
                    return false;
                }
            })
            ->sortBy(function ($game) use ($now, $timezone) {
                return \Carbon\Carbon::parse(
                    $now->format('Y-m-d') . ' ' . trim($game->result_time),
                    $timezone
                )->timestamp;
            });

        /*
        |--------------------------------------------------------------------------
        | Future Upcoming Games
        |--------------------------------------------------------------------------
        */
        $futureGames = $games
            ->filter(function ($game) use ($todayTableResults, $now, $timezone) {
                if (isset($todayTableResults[$game->id]) || empty($game->result_time)) {
                    return false;
                }

                try {
                    $gameTime = \Carbon\Carbon::parse(
                        $now->format('Y-m-d') . ' ' . trim($game->result_time),
                        $timezone
                    );

                    return $gameTime->greaterThan($now);
                } catch (\Throwable $e) {
                    return false;
                }
            })
            ->sortBy(function ($game) use ($now, $timezone) {
                return \Carbon\Carbon::parse(
                    $now->format('Y-m-d') . ' ' . trim($game->result_time),
                    $timezone
                )->timestamp;
            });

        /*
        |--------------------------------------------------------------------------
        | Final Live Games
        |--------------------------------------------------------------------------
        */
        $liveGames = $declaredGames
            ->concat($waitingGames)
            ->concat($futureGames)
            ->unique('id')
            ->take(4);

        $startDate = now($timezone)->startOfMonth();
        $endDate = now($timezone)->endOfMonth();

        $dates = CarbonPeriod::create($startDate, $endDate);

        $monthlyResults = GameResult::query()
            ->select([
                'id',
                'game_id',
                'result_date',
                'result',
                'status',
            ])
            ->whereIn('game_id', $gameIds)
            ->whereBetween('result_date', [
                $startDate->format('Y-m-d'),
                $endDate->format('Y-m-d'),
            ])
            ->where('status', 'declared')
            ->get()
            ->groupBy(function ($result) use ($timezone) {
                return \Carbon\Carbon::parse($result->result_date, $timezone)
                    ->format('Y-m-d');
            });

        $seo = SeoPage::query()
            ->where('page_key', 'home')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Advertisements
        |--------------------------------------------------------------------------
        | Logic same hai. 4 query ke jagah 1 query.
        */
        $allAdvertisements = Advertisement::query()
            ->select([
                'id',
                'title',
                'content',
                'image',
                'link',
                'position',
                'is_active',
                'created_at',
            ])
            ->where('is_active', true)
            ->whereIn('position', ['top', 'middle', 'bottom', 'sidebar'])
            ->latest()
            ->get()
            ->groupBy('position');

        $advertisements = $allAdvertisements->get('top', collect());
        $topAdvertisements = $advertisements;

        $middleAdvertisement = $allAdvertisements->get('middle', collect())->first();
        $bottomAdvertisement = $allAdvertisements->get('bottom', collect())->first();
        $sidebarAdvertisement = $allAdvertisements->get('sidebar', collect())->first();

        return view('front.home.index', compact(
            'games',
            'gameSections',
            'chartGameSections',
            'dates',
            'monthlyResults',
            'seo',
            'advertisements',
            'topAdvertisements',
            'middleAdvertisement',
            'bottomAdvertisement',
            'sidebarAdvertisement',
            'todayResults',
            'todayTableResults',
            'yesterdayResults',
            'today',
            'yesterday',
            'liveGames'
        ));
    }



    public function homeWithTime()
    {
        $timezone = 'Asia/Kolkata';

        $now = now($timezone);
        $today = $now->toDateString();
        $yesterday = $now->copy()->subDay()->toDateString();

        /*
    |--------------------------------------------------------------------------
    | Active Games
    |--------------------------------------------------------------------------
    */
        $games = Game::query()
            ->select([
                'id',
                'name',
                'slug',
                'result_time',
                'sort_order',
                'is_active',
            ])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $gameIds = $games->pluck('id');

        $gameSections = $games->chunk(18);
        $chartGameSections = $games->chunk(18);

        /*
    |--------------------------------------------------------------------------
    | Today Table Results
    |--------------------------------------------------------------------------
    |
    | Sirf aaj ke declared results.
    |
    */
        $todayTableResults = GameResult::query()
            ->select([
                'id',
                'game_id',
                'result_date',
                'result',
                'status',
                'show_minutes',
                'updated_at',
            ])
            ->whereIn('game_id', $gameIds)
            ->whereDate('result_date', $today)
            ->where('status', 'declared')
            ->whereNotNull('result')
            ->where('result', '!=', '')
            ->orderByDesc('updated_at')
            ->get()
            ->unique('game_id')
            ->keyBy('game_id');

        /*
    |--------------------------------------------------------------------------
    | Today Live Declared Results
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Pehle yesterday + today dono results aa rahe the.
    | Is wajah se new game me purana result jaise 58 dikh raha tha.
    |
    | Ab sirf aaj ka result live section me aayega.
    |
    */
        $todayResults = GameResult::query()
            ->select([
                'id',
                'game_id',
                'result_date',
                'result',
                'status',
                'show_minutes',
                'updated_at',
            ])
            ->whereIn('game_id', $gameIds)
            ->whereDate('result_date', $today)
            ->where('status', 'declared')
            ->whereNotNull('result')
            ->where('result', '!=', '')
            ->orderByDesc('updated_at')
            ->get()
            ->filter(function ($result) use ($timezone) {

                $showMinutes = (int) ($result->show_minutes ?? 0);

                /*
            |--------------------------------------------------------------------------
            | show_minutes <= 0
            |--------------------------------------------------------------------------
            |
            | Agar show_minutes set nahi hai ya 0 hai,
            | result ko live result me allow karenge.
            |
            */
                if ($showMinutes <= 0) {
                    return true;
                }

                $updatedAt = Carbon::parse($result->updated_at)
                    ->timezone($timezone);

                $hideAfter = $updatedAt->copy()->addMinutes($showMinutes);

                return now($timezone)->lessThanOrEqualTo($hideAfter);
            })
            ->unique('game_id')
            ->keyBy('game_id');

        /*
    |--------------------------------------------------------------------------
    | Yesterday Results
    |--------------------------------------------------------------------------
    */
        $yesterdayResults = GameResult::query()
            ->select([
                'id',
                'game_id',
                'result_date',
                'result',
                'status',
                'updated_at',
            ])
            ->whereIn('game_id', $gameIds)
            ->whereDate('result_date', $yesterday)
            ->where('status', 'declared')
            ->whereNotNull('result')
            ->where('result', '!=', '')
            ->orderByDesc('updated_at')
            ->get()
            ->unique('game_id')
            ->keyBy('game_id');

        /*
    |--------------------------------------------------------------------------
    | Declared Live Games
    |--------------------------------------------------------------------------
    |
    | Jin games ka aaj result declared hai,
    | unko sabse pehle live list me dikhayenge.
    |
    */
        $declaredGames = $games
            ->filter(function ($game) use ($todayResults) {

                return isset($todayResults[$game->id])
                    && filled($todayResults[$game->id]->result);
            })
            ->sortByDesc(function ($game) use ($todayResults, $timezone) {

                return Carbon::parse(
                    $todayResults[$game->id]->updated_at
                )
                    ->timezone($timezone)
                    ->timestamp;
            });

        /*
    |--------------------------------------------------------------------------
    | Waiting Games
    |--------------------------------------------------------------------------
    |
    | Rule:
    |
    | 1. Aaj result declared nahi hona chahiye.
    | 2. result_time available hona chahiye.
    | 3. Result time se 5 minute pehle waiting start hogi.
    | 4. Result declare hone tak waiting rahegi.
    |
    | Example:
    | CHAMELI BAZAR = 12:30 PM
    |
    | 12:25 PM se waiting show hogi.
    | Result declare nahi hua to 1 PM, 2 PM etc. tak bhi waiting rahegi.
    |
    */
        $waitingGames = $games
            ->filter(function ($game) use ($todayTableResults) {

                /*
            | Aaj ka result already declared hai.
            */
                if (isset($todayTableResults[$game->id])) {
                    return false;
                }

                /*
            | Result time missing hai.
            */
                if (empty($game->result_time)) {
                    return false;
                }

                return true;
            })
            ->filter(function ($game) use ($now, $timezone) {

                try {

                    $gameTime = Carbon::parse(
                        $now->format('Y-m-d') . ' ' . trim($game->result_time),
                        $timezone
                    );

                    /*
                | Result time se 5 minute pehle waiting start.
                */
                    $waitingStart = $gameTime->copy()->subMinutes(5);

                    return $now->greaterThanOrEqualTo($waitingStart);
                } catch (\Throwable $e) {

                    return false;
                }
            })
            ->sortBy(function ($game) use ($now, $timezone) {

                try {

                    return Carbon::parse(
                        $now->format('Y-m-d') . ' ' . trim($game->result_time),
                        $timezone
                    )->timestamp;
                } catch (\Throwable $e) {

                    return PHP_INT_MAX;
                }
            });

        /*
    |--------------------------------------------------------------------------
    | Future Upcoming Games
    |--------------------------------------------------------------------------
    |
    | Jin games ka aaj result nahi hai aur result time future me hai.
    |
    */
        $futureGames = $games
            ->filter(function ($game) use ($todayTableResults, $now, $timezone) {

                /*
            | Result already declared.
            */
                if (isset($todayTableResults[$game->id])) {
                    return false;
                }

                /*
            | Result time nahi hai.
            */
                if (empty($game->result_time)) {
                    return false;
                }

                try {

                    $gameTime = Carbon::parse(
                        $now->format('Y-m-d') . ' ' . trim($game->result_time),
                        $timezone
                    );

                    /*
                | Waiting 5 minute pehle start hoti hai.
                | Isliye exact future games me 5 min window exclude karenge.
                */
                    $waitingStart = $gameTime->copy()->subMinutes(5);

                    return $now->lessThan($waitingStart);
                } catch (\Throwable $e) {

                    return false;
                }
            })
            ->sortBy(function ($game) use ($now, $timezone) {

                try {

                    return Carbon::parse(
                        $now->format('Y-m-d') . ' ' . trim($game->result_time),
                        $timezone
                    )->timestamp;
                } catch (\Throwable $e) {

                    return PHP_INT_MAX;
                }
            });

        /*
    |--------------------------------------------------------------------------
    | Final Live Games
    |--------------------------------------------------------------------------
    |
    | Priority:
    |
    | 1. Recently declared results
    | 2. Waiting games
    | 3. Upcoming games
    |
    */
        $liveGames = $declaredGames
            ->concat($waitingGames)
            ->concat($futureGames)
            ->unique('id')
            ->take(4);

        /*
    |--------------------------------------------------------------------------
    | Monthly Result Dates
    |--------------------------------------------------------------------------
    */
        $startDate = now($timezone)->startOfMonth();
        $endDate = now($timezone)->endOfMonth();

        $dates = CarbonPeriod::create($startDate, $endDate);

        /*
    |--------------------------------------------------------------------------
    | Monthly Results
    |--------------------------------------------------------------------------
    */
        $monthlyResults = GameResult::query()
            ->select([
                'id',
                'game_id',
                'result_date',
                'result',
                'status',
            ])
            ->whereIn('game_id', $gameIds)
            ->whereBetween('result_date', [
                $startDate->format('Y-m-d'),
                $endDate->format('Y-m-d'),
            ])
            ->where('status', 'declared')
            ->get()
            ->groupBy(function ($result) use ($timezone) {

                return Carbon::parse(
                    $result->result_date,
                    $timezone
                )->format('Y-m-d');
            });

        /*
    |--------------------------------------------------------------------------
    | SEO
    |--------------------------------------------------------------------------
    */
        $seo = SeoPage::query()
            ->where('page_key', 'home')
            ->first();

        /*
    |--------------------------------------------------------------------------
    | Advertisements
    |--------------------------------------------------------------------------
    */
        $allAdvertisements = Advertisement::query()
            ->select([
                'id',
                'title',
                'content',
                'image',
                'link',
                'position',
                'is_active',
                'created_at',
            ])
            ->where('is_active', true)
            ->whereIn('position', [
                'top',
                'middle',
                'bottom',
                'sidebar',
            ])
            ->latest()
            ->get()
            ->groupBy('position');

        $advertisements = $allAdvertisements->get(
            'top',
            collect()
        );

        $topAdvertisements = $advertisements;

        $middleAdvertisement = $allAdvertisements
            ->get('middle', collect())
            ->first();

        $bottomAdvertisement = $allAdvertisements
            ->get('bottom', collect())
            ->first();

        $sidebarAdvertisement = $allAdvertisements
            ->get('sidebar', collect())
            ->first();

        /*
    |--------------------------------------------------------------------------
    | Return Home View
    |--------------------------------------------------------------------------
    */
        return view('front.home.index', compact(
            'games',
            'gameSections',
            'chartGameSections',
            'dates',
            'monthlyResults',
            'seo',
            'advertisements',
            'topAdvertisements',
            'middleAdvertisement',
            'bottomAdvertisement',
            'sidebarAdvertisement',
            'todayResults',
            'todayTableResults',
            'yesterdayResults',
            'today',
            'yesterday',
            'liveGames'
        ));
    }

    public function chart()
    {
        $games = Game::query()
            ->where('is_active', true)
            ->with([
                'chartYears' => function ($query) {
                    $query->where('is_active', true)
                        ->orderByDesc('year');
                }
            ])
            ->orderBy('sort_order')
            ->get();

        $seo = SeoPage::where('page_key', 'chart')->first();

        return view('front.chart.index', compact('games', 'seo'));
    }








    public function gameRecord(string $slug)
    {
        $game = Game::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $year = now('Asia/Kolkata')->year;

        $results = GameResult::where('game_id', $game->id)
            ->whereYear('result_date', $year)
            ->orderBy('result_date')
            ->get();

        $seo = SeoPage::where('game_id', $game->id)
            ->whereNull('year')
            ->first();

        if (!$seo) {
            $seo = SeoPage::where('page_key', 'game-record')->first();
        }

        $canonicalUrl = route('game.record', $game->slug);

        if ($seo) {
            $seo = clone $seo;
            $seo->canonical_url = $canonicalUrl;

            $replace = [
                '{game}' => $game->name,
                '{slug}' => $game->slug,
                '{year}' => $year,
            ];

            $seo->meta_title = $seo->meta_title
                ? str_replace(array_keys($replace), array_values($replace), $seo->meta_title)
                : null;

            $seo->meta_description = $seo->meta_description
                ? str_replace(array_keys($replace), array_values($replace), $seo->meta_description)
                : null;

            $seo->meta_keywords = $seo->meta_keywords
                ? str_replace(array_keys($replace), array_values($replace), $seo->meta_keywords)
                : null;

            $seo->og_title = $seo->og_title
                ? str_replace(array_keys($replace), array_values($replace), $seo->og_title)
                : null;

            $seo->og_description = $seo->og_description
                ? str_replace(array_keys($replace), array_values($replace), $seo->og_description)
                : null;
        } else {
            $seo = (object) [
                'meta_title'       => "{$game->name} Record Chart",
                'meta_description' => "{$game->name} record chart, old result and complete satta chart.",
                'meta_keywords'    => "{$game->name} record, {$game->name} chart",
                'canonical_url'    => $canonicalUrl,
                'og_title'         => null,
                'og_description'   => null,
                'og_image'         => null,
                'schema_markup'    => null,
            ];
        }

        $contentBlocks = ContentBlock::where('game_id', $game->id)
            ->whereNull('year')
            ->where('is_active', true)
            ->orderBy('id')
            ->get();

        return view('front.game.record', compact(
            'game',
            'results',
            'year',
            'seo',
            'contentBlocks'
        ));
    }


    public function yearRecord(string $slug, int $year)
    {
        $game = Game::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $results = GameResult::where('game_id', $game->id)
            ->whereYear('result_date', $year)
            ->orderBy('result_date')
            ->get();

        $seo = SeoPage::where('game_id', $game->id)
            ->where('year', $year)
            ->first();

        if (!$seo) {
            $seo = SeoPage::where('page_key', 'year-record')->first();
        }

        $canonicalUrl = route('game.year-record', [$game->slug, $year]);

        if ($seo) {
            $seo = clone $seo;
            $seo->canonical_url = $canonicalUrl;

            $replace = [
                '{game}' => $game->name,
                '{slug}' => $game->slug,
                '{year}' => $year,
            ];

            $seo->meta_title = $seo->meta_title
                ? str_replace(array_keys($replace), array_values($replace), $seo->meta_title)
                : null;

            $seo->meta_description = $seo->meta_description
                ? str_replace(array_keys($replace), array_values($replace), $seo->meta_description)
                : null;

            $seo->meta_keywords = $seo->meta_keywords
                ? str_replace(array_keys($replace), array_values($replace), $seo->meta_keywords)
                : null;

            $seo->og_title = $seo->og_title
                ? str_replace(array_keys($replace), array_values($replace), $seo->og_title)
                : null;

            $seo->og_description = $seo->og_description
                ? str_replace(array_keys($replace), array_values($replace), $seo->og_description)
                : null;
        } else {
            $seo = (object) [
                'meta_title'       => "{$game->name} {$year} Record Chart",
                'meta_description' => "{$game->name} {$year} record chart, old result and complete satta chart.",
                'meta_keywords'    => "{$game->name} {$year} record, {$game->name} {$year} chart",
                'canonical_url'    => $canonicalUrl,
                'og_title'         => null,
                'og_description'   => null,
                'og_image'         => null,
                'schema_markup'    => null,
            ];
        }

        $contentBlocks = ContentBlock::where('game_id', $game->id)
            ->where('year', $year)
            ->where('is_active', true)
            ->orderBy('id')
            ->get();

        return view('front.game.year_record', compact(
            'game',
            'results',
            'year',
            'seo',
            'contentBlocks'
        ));
    }








    public function contactUs()
    {
        $seo = SeoPage::where('page_key', 'contact-us')->first();
        return view('front.contact-us.index', compact('seo'));
    }

    public function privacyPolicy()
    {
        $seo = SeoPage::where('page_key', 'privacy-policy')->first();
        return view('front.privacy-policy.index', compact('seo'));
    }

    public function termsConditions()
    {
        $seo = SeoPage::where('page_key', 'terms-conditions')->first();
        return view('front.terms-conditions.index', compact('seo'));
    }
}
