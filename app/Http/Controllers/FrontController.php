<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\GameResult;
use App\Models\Advertisement;
use App\Models\SeoPage;
use Carbon\CarbonPeriod;


class FrontController extends Controller
{


   public function homeold()
{
    $today = now('Asia/Kolkata')->toDateString();
    $yesterday = now('Asia/Kolkata')->subDay()->toDateString();

    $games = Game::where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    $chartGames = $games;

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

    $startDate = now('Asia/Kolkata')->startOfMonth();
    $endDate = now('Asia/Kolkata')->endOfMonth();

    $dates = CarbonPeriod::create($startDate, $endDate);

    $monthlyResults = GameResult::whereBetween('result_date', [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d'),
        ])
        ->where('status', 'declared')
        ->get()
        ->groupBy(function ($result) {
            return \Carbon\Carbon::parse($result->result_date)->format('Y-m-d');
        });

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
        'chartGames',
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
        'yesterday'
    ));
}


public function home2nd()
{
    $today = now('Asia/Kolkata')->toDateString();
    $yesterday = now('Asia/Kolkata')->subDay()->toDateString();

    $games = Game::where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    // 17-17 games ke section banenge
    $gameSections = $games->chunk(18);
    $chartGameSections = $games->chunk(18);

    $chartGames = $games;

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

    $startDate = now('Asia/Kolkata')->startOfMonth();
    $endDate = now('Asia/Kolkata')->endOfMonth();

    $dates = CarbonPeriod::create($startDate, $endDate);

    $monthlyResults = GameResult::whereBetween('result_date', [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d'),
        ])
        ->where('status', 'declared')
        ->get()
        ->groupBy(function ($result) {
            return \Carbon\Carbon::parse($result->result_date)->format('Y-m-d');
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
        'chartGames',
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
        'yesterday'
    ));
}




public function home()
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




    // public function gameRecord(string $slug)
    // {
    //     $game = Game::where('slug', $slug)
    //         ->where('is_active', true)
    //         ->firstOrFail();

    //     $year = now()->year;

    //     $results = GameResult::where('game_id', $game->id)
    //         ->whereYear('result_date', $year)
    //         ->orderBy('result_date')
    //         ->get();

    //     $seo = SeoPage::where('page_key', 'game-record')->first();

    //     return view('front.game.record', compact('game', 'results', 'year', 'seo'));
    // }

    // public function yearRecord(string $slug, int $year)
    // {
    //     $game = Game::where('slug', $slug)
    //         ->where('is_active', true)
    //         ->firstOrFail();

    //     $results = GameResult::where('game_id', $game->id)
    //         ->whereYear('result_date', $year)
    //         ->orderBy('result_date')
    //         ->get();

    //     $seo = SeoPage::where('page_key', 'year-record')->first();

    //     return view('front.game.year_record', compact('game', 'results', 'year', 'seo'));
    // }









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

    return view('front.game.record', compact('game', 'results', 'year', 'seo'));
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

    return view('front.game.year_record', compact('game', 'results', 'year', 'seo'));
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
