<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use App\Models\Game;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;
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


          View::composer('front.layouts.footer', function ($view) {
            $footerGames = Game::query()
                ->where('is_active', true)
                ->with([
                    'chartYears' => function ($query) {
                        $query->where('is_active', true)
                            ->orderByDesc('year');
                    }
                ])
                ->orderBy('sort_order')
                ->get();

            $view->with('footerGames', $footerGames);
        });




        Gate::before(function ($user, $ability) {
        return $user->hasRole('super admin') ? true : null;
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
