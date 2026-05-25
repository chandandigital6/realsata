<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use App\Models\Game;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider

{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();


     View::composer('front.layouts.header', function ($view) {

            $apiBaseUrl = rtrim(config('services.main_api.url'), '/');
            $today = Carbon::today('Asia/Kolkata')->format('Y-m-d');

            $headerGames = collect();

            try {
                $response = Http::timeout(10)->get($apiBaseUrl . '/games-results', [
                    'date' => $today,
                ]);

                if ($response->successful()) {
                    $headerGames = collect($response->json('games', []))->map(function ($game) {
                        return (object) [
                            'id' => $game['id'] ?? null,
                            'name' => $game['name'] ?? '',
                            'slug' => $game['slug'] ?? '',
                            'result_time' => $game['result_time'] ?? null,

                            'todayResult' => (object) [
                                'result' => $game['result']['result'] ?? null,
                                'status' => $game['result']['status'] ?? 'waiting',
                                'show_minutes' => $game['result']['show_minutes'] ?? 10,
                                'updated_at' => $game['result']['updated_at'] ?? null,
                            ],
                        ];
                    });
                }
            } catch (\Throwable $e) {
                $headerGames = collect();
            }

            $view->with('headerGames', $headerGames);
        });

    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
