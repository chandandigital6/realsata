<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\GameResult;
use App\Models\Advertisement;
use App\Models\SeoPage;
use Carbon\CarbonPeriod;
use App\Models\ContentBlock;


class FrontController extends Controller
{




public function homeWorking()
{
    $today = now('Asia/Kolkata')->toDateString();
    $yesterday = now('Asia/Kolkata')->subDay()->toDateString();
    $now = now('Asia/Kolkata');

    $games = Game::where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    // 17 games per section
    $gameSections = $games->chunk(17);
    $chartGameSections = $games->chunk(17);

    $todayResults = GameResult::whereDate('result_date', $today)
        ->where('status', 'declared')
        ->whereNotNull('result')
        ->where('result', '!=', '')
        ->latest('updated_at')
        ->get()
        ->keyBy('game_id');

    $yesterdayResults = GameResult::whereDate('result_date', $yesterday)
        ->where('status', 'declared')
        ->whereNotNull('result')
        ->where('result', '!=', '')
        ->latest('updated_at')
        ->get()
        ->keyBy('game_id');

    // Result updated game top par
    $declaredGames = $games
        ->filter(function ($game) use ($todayResults) {
            return isset($todayResults[$game->id]) && filled($todayResults[$game->id]->result);
        })
        ->filter(function ($game) use ($todayResults) {
            $result = $todayResults[$game->id];

            if ((int) ($result->show_minutes ?? 0) <= 0) {
                return true;
            }

            $expireTime = \Carbon\Carbon::parse($result->updated_at, 'Asia/Kolkata')
                ->addMinutes((int) $result->show_minutes);

            return now('Asia/Kolkata')->lessThanOrEqualTo($expireTime);
        })
        ->sortByDesc(function ($game) use ($todayResults) {
            return \Carbon\Carbon::parse($todayResults[$game->id]->updated_at, 'Asia/Kolkata')->timestamp;
        });

    // Sirf current time ke baad wale games
    $upcomingGames = $games
        ->filter(function ($game) use ($todayResults, $now) {
            if (isset($todayResults[$game->id])) {
                return false;
            }

            if (empty($game->result_time)) {
                return false;
            }

            try {
                $gameTime = \Carbon\Carbon::parse(
                    $now->format('Y-m-d') . ' ' . trim($game->result_time),
                    'Asia/Kolkata'
                );

                return $gameTime->greaterThanOrEqualTo($now);
            } catch (\Exception $e) {
                return false;
            }
        })
        ->sortBy(function ($game) use ($now) {
            return \Carbon\Carbon::parse(
                $now->format('Y-m-d') . ' ' . trim($game->result_time),
                'Asia/Kolkata'
            )->timestamp;
        });

    $liveGames = $declaredGames->concat($upcomingGames)->take(4);

    $startDate = now('Asia/Kolkata')->startOfMonth();
    $endDate = now('Asia/Kolkata')->endOfMonth();

    $dates = CarbonPeriod::create($startDate, $endDate);

    $monthlyResults = GameResult::whereBetween('result_date', [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d'),
        ])
        ->where('status', 'declared')
        ->get()
        ->groupBy(fn ($result) => \Carbon\Carbon::parse($result->result_date)->format('Y-m-d'));

    $seo = SeoPage::where('page_key', 'home')->first();

    $advertisements = Advertisement::where('is_active', true)
        ->where('position', 'top')
        ->latest()
        ->get();

    $topAdvertisements = $advertisements;

    $middleAdvertisement = Advertisement::where('is_active', true)->where('position', 'middle')->latest()->first();
    $bottomAdvertisement = Advertisement::where('is_active', true)->where('position', 'bottom')->latest()->first();
    $sidebarAdvertisement = Advertisement::where('is_active', true)->where('position', 'sidebar')->latest()->first();

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
        'yesterdayResults',
        'today',
        'yesterday',
        'liveGames'
    ));
}




public function home222()
{
    $timezone = 'Asia/Kolkata';

    $now = now($timezone);
    $today = $now->toDateString();
    $yesterday = $now->copy()->subDay()->toDateString();

    $games = Game::where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    // 17 games per section
    $gameSections = $games->chunk(17);
    $chartGameSections = $games->chunk(17);

    /*
    |--------------------------------------------------------------------------
    | Today Table Results
    |--------------------------------------------------------------------------
    | Ye table ke "आज का रिज़ल्ट" column ke liye hai.
    | Isme sirf aaj ka actual result aayega.
    | Is par show_minutes ka filter nahi lagega.
    */
    $todayTableResults = GameResult::whereDate('result_date', $today)
        ->where('status', 'declared')
        ->whereNotNull('result')
        ->where('result', '!=', '')
        ->latest('updated_at')
        ->get()
        ->keyBy('game_id');

    /*
    |--------------------------------------------------------------------------
    | Live Results
    |--------------------------------------------------------------------------
    | Ye upper live result box ke liye hai.
    | Isme yesterday + today result aayega.
    | Late night result midnight ke baad bhi show_minutes tak show hoga.
    */
    $todayResults = GameResult::whereBetween('result_date', [
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

            $updatedAt = \Carbon\Carbon::parse($result->updated_at, $timezone);
            $expireTime = $updatedAt->copy()->addMinutes($showMinutes);

            return now($timezone)->lessThanOrEqualTo($expireTime);
        })
        ->unique('game_id')
        ->keyBy('game_id');

    /*
    |--------------------------------------------------------------------------
    | Yesterday Results
    |--------------------------------------------------------------------------
    | Ye table ke "कल आया था" column ke liye hai.
    */
    $yesterdayResults = GameResult::whereDate('result_date', $yesterday)
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
    | Jo live result currently show_minutes ke andar hai, wo top par.
    */
    $declaredGames = $games
        ->filter(function ($game) use ($todayResults) {
            return isset($todayResults[$game->id])
                && filled($todayResults[$game->id]->result);
        })
        ->sortByDesc(function ($game) use ($todayResults, $timezone) {
            return \Carbon\Carbon::parse(
                $todayResults[$game->id]->updated_at,
                $timezone
            )->timestamp;
        });

    /*
    |--------------------------------------------------------------------------
    | Upcoming Games
    |--------------------------------------------------------------------------
    | Jinka result visible nahi hai aur time abhi aana baaki hai.
    */
    $upcomingGames = $games
        ->filter(function ($game) use ($todayResults, $now, $timezone) {
            if (isset($todayResults[$game->id])) {
                return false;
            }

            if (empty($game->result_time)) {
                return false;
            }

            try {
                $gameTime = \Carbon\Carbon::parse(
                    $now->format('Y-m-d') . ' ' . trim($game->result_time),
                    $timezone
                );

                return $gameTime->greaterThanOrEqualTo($now);
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

    $liveGames = $declaredGames
        ->concat($upcomingGames)
        ->take(4);

    $startDate = now($timezone)->startOfMonth();
    $endDate = now($timezone)->endOfMonth();

    $dates = CarbonPeriod::create($startDate, $endDate);

    $monthlyResults = GameResult::whereBetween('result_date', [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d'),
        ])
        ->where('status', 'declared')
        ->get()
        ->groupBy(function ($result) use ($timezone) {
            return \Carbon\Carbon::parse($result->result_date, $timezone)
                ->format('Y-m-d');
        });

    $seo = SeoPage::where('page_key', 'home')->first();

    $advertisements = Advertisement::where('is_active', true)
        ->where('position', 'top')
        ->latest()
        ->get();

    $topAdvertisements = $advertisements;

    $middleAdvertisement = Advertisement::where('is_active', true)
        ->where('position', 'middle')
        ->latest()
        ->first();

    $bottomAdvertisement = Advertisement::where('is_active', true)
        ->where('position', 'bottom')
        ->latest()
        ->first();

    $sidebarAdvertisement = Advertisement::where('is_active', true)
        ->where('position', 'sidebar')
        ->latest()
        ->first();

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



public function home33333()
{
    $timezone = 'Asia/Kolkata';

    $now = now($timezone);
    $today = $now->toDateString();
    $yesterday = $now->copy()->subDay()->toDateString();

    $games = Game::where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    $gameSections = $games->chunk(17);
    $chartGameSections = $games->chunk(17);

    /*
    |--------------------------------------------------------------------------
    | Today Table Results
    |--------------------------------------------------------------------------
    */
    $todayTableResults = GameResult::whereDate('result_date', $today)
        ->where('status', 'declared')
        ->whereNotNull('result')
        ->where('result', '!=', '')
        ->latest('updated_at')
        ->get()
        ->keyBy('game_id');

    /*
    |--------------------------------------------------------------------------
    | Live Results
    |--------------------------------------------------------------------------
    | Result aane ke baad sirf show_minutes tak live box me dikhega.
    */
    $todayResults = GameResult::whereBetween('result_date', [
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

            $expireTime = $updatedAt->copy()->addMinutes($showMinutes);

            return now($timezone)->lessThanOrEqualTo($expireTime);
        })
        ->unique('game_id')
        ->keyBy('game_id');

    /*
    |--------------------------------------------------------------------------
    | Yesterday Results
    |--------------------------------------------------------------------------
    */
    $yesterdayResults = GameResult::whereDate('result_date', $yesterday)
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
    | Jo result abhi show_minutes ke andar hai, wo sabse top me dikhega.
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
    | Upcoming Waiting Games
    |--------------------------------------------------------------------------
    | Game ke result_time se sirf 5 minute pehle WAIT dikhana hai.
    | Example: DISAWAR 02:00 AM hai to 01:55 AM se WAIT show hoga.
    */
    $upcomingGames = $games
        ->filter(function ($game) use ($todayResults, $now, $timezone) {
            if (isset($todayResults[$game->id])) {
                return false;
            }

            if (empty($game->result_time)) {
                return false;
            }

            try {
                $gameTime = \Carbon\Carbon::parse(
                    $now->format('Y-m-d') . ' ' . trim($game->result_time),
                    $timezone
                );

                $waitStartTime = $gameTime->copy()->subMinutes(5);

                return $now->betweenIncluded($waitStartTime, $gameTime);
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
        ->concat($upcomingGames)
        ->take(4);

    $startDate = now($timezone)->startOfMonth();
    $endDate = now($timezone)->endOfMonth();

    $dates = CarbonPeriod::create($startDate, $endDate);

    $monthlyResults = GameResult::whereBetween('result_date', [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d'),
        ])
        ->where('status', 'declared')
        ->get()
        ->groupBy(function ($result) use ($timezone) {
            return \Carbon\Carbon::parse($result->result_date, $timezone)
                ->format('Y-m-d');
        });

    $seo = SeoPage::where('page_key', 'home')->first();

    $advertisements = Advertisement::where('is_active', true)
        ->where('position', 'top')
        ->latest()
        ->get();

    $topAdvertisements = $advertisements;

    $middleAdvertisement = Advertisement::where('is_active', true)
        ->where('position', 'middle')
        ->latest()
        ->first();

    $bottomAdvertisement = Advertisement::where('is_active', true)
        ->where('position', 'bottom')
        ->latest()
        ->first();

    $sidebarAdvertisement = Advertisement::where('is_active', true)
        ->where('position', 'sidebar')
        ->latest()
        ->first();

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





public function hometest()
{
    $timezone = 'Asia/Kolkata';

    $now = now($timezone);
    $today = $now->toDateString();
    $yesterday = $now->copy()->subDay()->toDateString();

    $games = Game::where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    $gameSections = $games->chunk(17);
    $chartGameSections = $games->chunk(17);

    /*
    |--------------------------------------------------------------------------
    | Today Table Results
    |--------------------------------------------------------------------------
    */
    $todayTableResults = GameResult::whereDate('result_date', $today)
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
    $todayResults = GameResult::whereBetween('result_date', [
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
    $yesterdayResults = GameResult::whereDate('result_date', $yesterday)
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
    | Next 4 Waiting Games
    |--------------------------------------------------------------------------
    | Live box me 4 games hamesha dikhenge.
    | Jinka result nahi aaya hai wo waiting me dikhenge.
    | Time ke hisaab se next upcoming games flow me aayenge.
    */
    $upcomingGames = $games
        ->filter(function ($game) use ($todayTableResults) {
            if (isset($todayTableResults[$game->id])) {
                return false;
            }

            return !empty($game->result_time);
        })
        ->sortBy(function ($game) use ($now, $timezone) {
            try {
                $gameTime = \Carbon\Carbon::parse(
                    $now->format('Y-m-d') . ' ' . trim($game->result_time),
                    $timezone
                );

                if ($gameTime->lessThan($now)) {
                    $gameTime->addDay();
                }

                return $gameTime->timestamp;
            } catch (\Throwable $e) {
                return PHP_INT_MAX;
            }
        })
        ->take(4);

    /*
    |--------------------------------------------------------------------------
    | Final Live Games
    |--------------------------------------------------------------------------
    */
    $liveGames = $declaredGames
        ->concat($upcomingGames)
        ->unique('id')
        ->take(4);

    $startDate = now($timezone)->startOfMonth();
    $endDate = now($timezone)->endOfMonth();

    $dates = CarbonPeriod::create($startDate, $endDate);

    $monthlyResults = GameResult::whereBetween('result_date', [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d'),
        ])
        ->where('status', 'declared')
        ->get()
        ->groupBy(function ($result) use ($timezone) {
            return \Carbon\Carbon::parse($result->result_date, $timezone)
                ->format('Y-m-d');
        });

    $seo = SeoPage::where('page_key', 'home')->first();

    $advertisements = Advertisement::where('is_active', true)
        ->where('position', 'top')
        ->latest()
        ->get();

    $topAdvertisements = $advertisements;

    $middleAdvertisement = Advertisement::where('is_active', true)
        ->where('position', 'middle')
        ->latest()
        ->first();

    $bottomAdvertisement = Advertisement::where('is_active', true)
        ->where('position', 'bottom')
        ->latest()
        ->first();

    $sidebarAdvertisement = Advertisement::where('is_active', true)
        ->where('position', 'sidebar')
        ->latest()
        ->first();

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





public function home()
{
    $timezone = 'Asia/Kolkata';

    $now = now($timezone);
    $today = $now->toDateString();
    $yesterday = $now->copy()->subDay()->toDateString();

    $games = Game::where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    $gameSections = $games->chunk(17);
    $chartGameSections = $games->chunk(17);

    /*
    |--------------------------------------------------------------------------
    | Today Table Results
    |--------------------------------------------------------------------------
    */
    $todayTableResults = GameResult::whereDate('result_date', $today)
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
    | Result aane ke baad game top me dikhega.
    | show_minutes ke according result live box me visible rahega.
    */
    $todayResults = GameResult::whereBetween('result_date', [
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
    $yesterdayResults = GameResult::whereDate('result_date', $yesterday)
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
    | Jis game ka result aa gaya hai wo top me show hoga.
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
    | Game result_time se 5 minute pehle waiting me show hoga.
    | Agar result nahi aaya to 45 minute tak waiting me rahega.
    |
    | Example:
    | SHRI GANESH 04:10 PM
    | Show Start: 04:05 PM
    | Show End:   04:55 PM
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
    | Live box ko 4 item complete karne ke liye next upcoming games add honge.
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
    | 1. Declared games top me
    | 2. Waiting games uske baad
    | 3. Future upcoming games se 4 box complete
    */
    $liveGames = $declaredGames
        ->concat($waitingGames)
        ->concat($futureGames)
        ->unique('id')
        ->take(4);

    $startDate = now($timezone)->startOfMonth();
    $endDate = now($timezone)->endOfMonth();

    $dates = CarbonPeriod::create($startDate, $endDate);

    $monthlyResults = GameResult::whereBetween('result_date', [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d'),
        ])
        ->where('status', 'declared')
        ->get()
        ->groupBy(function ($result) use ($timezone) {
            return \Carbon\Carbon::parse($result->result_date, $timezone)
                ->format('Y-m-d');
        });

    $seo = SeoPage::where('page_key', 'home')->first();

    $advertisements = Advertisement::where('is_active', true)
        ->where('position', 'top')
        ->latest()
        ->get();

    $topAdvertisements = $advertisements;

    $middleAdvertisement = Advertisement::where('is_active', true)
        ->where('position', 'middle')
        ->latest()
        ->first();

    $bottomAdvertisement = Advertisement::where('is_active', true)
        ->where('position', 'bottom')
        ->latest()
        ->first();

    $sidebarAdvertisement = Advertisement::where('is_active', true)
        ->where('position', 'sidebar')
        ->latest()
        ->first();

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

    $year = now()->year;

    $results = GameResult::where('game_id', $game->id)
        ->whereYear('result_date', $year)
        ->orderBy('result_date')
        ->get();

    $seo = SeoPage::where(function ($q) use ($game, $year) {
        $q->where('game_id', $game->id)
          ->where('year', $year);
    })
    ->orWhere(function ($q) use ($game) {
        $q->where('game_id', $game->id)
          ->whereNull('year');
    })
    ->orWhere('page_key', 'year-record')
    ->first();

    $contentBlocks = ContentBlock::where('game_id', $game->id)
    ->where('is_active', true)
    ->orderBy('id')
    ->get();
    return view('front.game.record', compact('game', 'results', 'year', 'seo','contentBlocks'));
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

    $seo = SeoPage::where(function ($q) use ($game, $year) {
        $q->where('game_id', $game->id)
          ->where('year', $year);
    })
    ->orWhere(function ($q) use ($game) {
        $q->where('game_id', $game->id)
          ->whereNull('year');
    })
    ->orWhere('page_key', 'year-record')
    ->first();

   $contentBlocks = ContentBlock::where('game_id', $game->id)
    ->where('is_active', true)
    ->orderBy('id')
    ->get();

return view('front.game.year_record', compact('game', 'results', 'year', 'seo', 'contentBlocks'));

}



    public function products()
    {
        $seo = SeoPage::where('page_key', 'products')->first();

        return view('front.products.index', compact('seo'));
    }

    public function singleProduct()
    {
        // $this->seo()->setTitle("Product Name");
        return view('front.products.single');
    }

    public function services()
    {
        // $this->seo()->setTitle("Services");
        return view('front.services.index');
    }

    public function aboutUs()
    {
        $seo = SeoPage::where('page_key', 'about-us')->first();
        return view('front.chart.index', compact('seo'));
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
