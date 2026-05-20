<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\GameResult;
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

    return view('front.home.index', compact(
        'games',
        'chartGames',
        'dates',
        'monthlyResults'
    ));
}
    
    

    
    public function chart()
{
   

    return view('front.chart.index');
}

    public function products()
    {
        // $this->seo()->setTitle("Products");
        return view('front.products.index');
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
        // $this->seo()->setTitle("About Us");
        return view('front.chart.index');
    }

    public function contactUs()
    {
        // $this->seo()->setTitle("Contact Us");
        return view('front.contact-us.index');
    }

    public function privacyPolicy()
    {
        // $this->seo()->setTitle("Privacy Policy");
        return view('front.privacy-policy.index');
    }

    public function termsConditions()
    {
        // $this->seo()->setTitle("Terms And Conditions");
        return view('front.terms-conditions.index');
    }
}
