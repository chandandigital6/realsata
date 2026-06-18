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

        $gameSections = $games->chunk(17);
        $chartGameSections = $games->chunk(17);

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



public function homeoooo()
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
