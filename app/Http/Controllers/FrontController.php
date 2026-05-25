<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class FrontController extends Controller
{
    private string $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = rtrim(config('services.main_api.url'), '/');
    }

    public function home()
    {
        $today = Carbon::today('Asia/Kolkata');
        $yesterday = Carbon::yesterday('Asia/Kolkata');

        $liveResponse = Http::timeout(10)->get($this->apiBaseUrl . '/live-results');
        $todayResponse = Http::timeout(10)->get($this->apiBaseUrl . '/games-results', [
            'date' => $today->format('Y-m-d'),
        ]);
        $yesterdayResponse = Http::timeout(10)->get($this->apiBaseUrl . '/games-results', [
            'date' => $yesterday->format('Y-m-d'),
        ]);

        $liveGames = $liveResponse->successful()
            ? collect($liveResponse->json('games', []))
            : collect();

        $todayGames = $todayResponse->successful()
            ? collect($todayResponse->json('games', []))
            : collect();

        $yesterdayGames = $yesterdayResponse->successful()
            ? collect($yesterdayResponse->json('games', []))->keyBy('slug')
            : collect();

        $games = $todayGames->map(function ($game) use ($yesterdayGames) {
            $yesterdayGame = $yesterdayGames->get($game['slug']);

            return (object) [
                'id' => $game['id'],
                'name' => $game['name'],
                'slug' => $game['slug'],
                'result_time' => $game['result_time'],
                'sort_order' => $game['sort_order'] ?? 0,

                'todayResult' => (object) [
                    'result' => $game['result']['result'] ?? null,
                    'status' => $game['result']['status'] ?? 'waiting',
                ],

                'yesterdayResult' => (object) [
                    'result' => $yesterdayGame['result']['result'] ?? null,
                    'status' => $yesterdayGame['result']['status'] ?? 'waiting',
                ],

                'latestResult' => (object) [
                    'result' => $game['result']['result'] ?? null,
                    'status' => $game['result']['status'] ?? 'waiting',
                ],
            ];
        });

        $chartGames = $games;

        $startDate = $today->copy()->startOfMonth();
        $endDate = $today->copy()->endOfMonth();
        $dates = CarbonPeriod::create($startDate, $endDate);

        $monthlyResults = collect();

        foreach ($dates as $date) {
            $response = Http::timeout(10)->get($this->apiBaseUrl . '/games-results', [
                'date' => $date->format('Y-m-d'),
            ]);

            if ($response->successful()) {
                $monthlyResults->put(
                    $date->format('Y-m-d'),
                    collect($response->json('games', []))->map(function ($game) {
                        return (object) [
                            'game_id' => $game['id'],
                            'game_slug' => $game['slug'],
                            'result_date' => $game['result']['result_date'] ?? null,
                            'result' => $game['result']['result'] ?? null,
                            'status' => $game['result']['status'] ?? 'waiting',
                        ];
                    })
                );
            }
        }

        return view('front.home.index', compact(
            'games',
            'chartGames',
            'dates',
            'monthlyResults'
        ));
    }


    public function chart()
{
    $response = Http::timeout(10)->get($this->apiBaseUrl . '/chart-games');

    $games = $response->successful()
        ? collect($response->json('games', []))->map(function ($game) {
            return (object) [
                'id' => $game['id'],
                'name' => $game['name'],
                'slug' => $game['slug'],
                'result_time' => $game['result_time'],
                'chartYears' => collect($game['chartYears'] ?? [])->map(function ($year) {
                    return (object) [
                        'year' => $year['year'],
                    ];
                }),
            ];
        })
        : collect();

    $seo = null;

    return view('front.chart.index', compact('games', 'seo'));
}

public function gameRecord(string $slug)
{
    return $this->yearRecord($slug, now('Asia/Kolkata')->year);
}

public function yearRecord(string $slug, int $year)
{
    $response = Http::timeout(10)->get($this->apiBaseUrl . "/game-year-record/{$slug}/{$year}");

    if ($response->successful()) {
        $apiData = $response->json();

        $game = (object) [
            'id' => $apiData['game']['id'] ?? null,
            'name' => $apiData['game']['name'] ?? ucwords(str_replace('-', ' ', $slug)),
            'slug' => $apiData['game']['slug'] ?? $slug,
            'result_time' => $apiData['game']['result_time'] ?? null,
        ];

        $results = collect($apiData['results'] ?? [])->map(function ($result) {
            return (object) [
                'result_date' => $result['result_date'],
                'result' => $result['result'],
                'status' => $result['status'] ?? 'waiting',
            ];
        });
    } else {
        $game = (object) [
            'name' => ucwords(str_replace('-', ' ', $slug)),
            'slug' => $slug,
        ];

        $results = collect();
    }

    $seo = null;

    return view('front.game.year_record', compact('game', 'results', 'year', 'seo'));
}
    

    public function contactUs()
    {
        return view('front.contact-us.index');
    }

    public function privacyPolicy()
    {
        return view('front.privacy-policy.index');
    }

    public function termsConditions()
    {
        return view('front.terms-conditions.index');
    }
}