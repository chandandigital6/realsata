<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\GameResult;
use App\Models\SeoPage;
use Carbon\CarbonPeriod;

class FrontController extends Controller
{
  public function home()
{
    $today = today();
    $yesterday = today()->subDay();

    $games = Game::with([
            'todayResult',
            'yesterdayResult',
            'latestResult',
        ])
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    $chartGames = Game::where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    $startDate = today()->startOfMonth();
    $endDate = today()->endOfMonth();

    $dates = CarbonPeriod::create($startDate, $endDate);

    $monthlyResults = GameResult::whereBetween('result_date', [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d'),
        ])
        ->get()
        ->groupBy(function ($result) {
            return $result->result_date->format('Y-m-d');
        });


           $seo = SeoPage::where('page_key', 'home')->first();


    return view('front.home.index', compact(
        'games',
        'chartGames',
        'dates',
        'monthlyResults',
        'seo'
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

        $seo = SeoPage::where('page_key', 'game-record')->first();

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

        $seo = SeoPage::where('page_key', 'year-record')->first();

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
