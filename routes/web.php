<?php

use App\Http\Controllers\FrontController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Middleware\EnsureTeamMembership;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\GameResultController;
use App\Http\Controllers\ContentBlockController;
use App\Http\Controllers\ChartYearController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\SeoPageController;


Route::get('/chart', [FrontController::class, 'chart'])->name('chart');
Route::get('/record/{slug}', [FrontController::class, 'gameRecord'])->name('game.record');
Route::get('/record/{slug}/{year}', [FrontController::class, 'yearRecord'])->name('game.yearRecord');
Route::get('/', [FrontController::class, 'home'])->name('home');
Route::any('/webhook', [FrontController::class, 'webhook'])->name('webhook');

// Route::get('/Chart', [FrontController::class, 'chart'])->name('chart');
// Route::get('/login', [LoginController::class,'index'])->name('index');
// Route::post('/logined-in', [LoginController::class,'login'])->name('login');
Route::get('/contact-us', [FrontController::class, 'contactUs'])->name('contact-us');
Route::get('/privacy-policy', [FrontController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms-conditions', [FrontController::class, 'termsConditions'])->name('terms-conditions');


Route::prefix('{current_team}')
    ->middleware(['auth', 'verified', EnsureTeamMembership::class])
    ->group(function () {
        Route::view('dashboard', 'dashboard')->name('dashboard');
    });

Route::middleware(['auth'])->group(function () {
    Route::resource('roles', RoleController::class);

    Route::delete('roles/{role}/permissions/{permission}', [RoleController::class, 'removePermission'])
        ->name('roles.permissions.remove');

    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::post('permissions/assign', [PermissionController::class, 'assign'])->name('permissions.assign');
    Route::post('permissions/store', [PermissionController::class, 'store'])->name('permissions.store');
    Route::delete('permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
    Route::get('/users', function () {
        return view('users.index');
    })->name('users.index');



    //game

    Route::get('games/index', [GameController::class, 'index'])->name('games.index');
    Route::get('games/create', [GameController::class, 'create'])->name('games.create');
    Route::post('games/store', [GameController::class, 'store'])->name('games.store');
    Route::get('games/edit/{game}', [GameController::class, 'edit'])->name('games.edit');
    Route::post('games/update/{game}', [GameController::class, 'update'])->name('games.update');
    Route::get('games/delete/{game}', [GameController::class, 'destroy'])->name('games.delete');

    //game result

    Route::get('game-results/index', [GameResultController::class, 'index'])->name('game-results.index');
    Route::get('game-results/create', [GameResultController::class, 'create'])->name('game-results.create');
    Route::post('game-results/store', [GameResultController::class, 'store'])->name('game-results.store');
    Route::get('game-results/edit/{gameResult}', [GameResultController::class, 'edit'])->name('game-results.edit');
    Route::post('game-results/update/{gameResult}', [GameResultController::class, 'update'])->name('game-results.update');
    Route::get('game-results/delete/{gameResult}', [GameResultController::class, 'destroy'])->name('game-results.delete');


    //contet block
    Route::get('content-blocks/index', [ContentBlockController::class, 'index'])->name('content-blocks.index');
    Route::get('content-blocks/create', [ContentBlockController::class, 'create'])->name('content-blocks.create');
    Route::post('content-blocks/store', [ContentBlockController::class, 'store'])->name('content-blocks.store');
    Route::get('content-blocks/edit/{contentBlock}', [ContentBlockController::class, 'edit'])->name('content-blocks.edit');
    Route::post('content-blocks/update/{contentBlock}', [ContentBlockController::class, 'update'])->name('content-blocks.update');
    Route::get('content-blocks/delete/{contentBlock}', [ContentBlockController::class, 'destroy'])->name('content-blocks.delete');




    Route::get('chart-years/index', [ChartYearController::class, 'index'])->name('chart-years.index');
    Route::get('chart-years/create', [ChartYearController::class, 'create'])->name('chart-years.create');
    Route::post('chart-years/store', [ChartYearController::class, 'store'])->name('chart-years.store');
    Route::get('chart-years/edit/{chartYear}', [ChartYearController::class, 'edit'])->name('chart-years.edit');
    Route::post('chart-years/update/{chartYear}', [ChartYearController::class, 'update'])->name('chart-years.update');
    Route::get('chart-years/delete/{chartYear}', [ChartYearController::class, 'destroy'])->name('chart-years.delete');


    //advertisement

    Route::get('advertisements/index', [AdvertisementController::class, 'index'])->name('advertisements.index');
    Route::get('advertisements/create', [AdvertisementController::class, 'create'])->name('advertisements.create');
    Route::post('advertisements/store', [AdvertisementController::class, 'store'])->name('advertisements.store');
    Route::get('advertisements/edit/{advertisement}', [AdvertisementController::class, 'edit'])->name('advertisements.edit');
    Route::post('advertisements/update/{advertisement}', [AdvertisementController::class, 'update'])->name('advertisements.update');
    Route::get('advertisements/delete/{advertisement}', [AdvertisementController::class, 'destroy'])->name('advertisements.delete');


    //notice

    Route::get('notices/index', [NoticeController::class, 'index'])->name('notices.index');
    Route::get('notices/create', [NoticeController::class, 'create'])->name('notices.create');
    Route::post('notices/store', [NoticeController::class, 'store'])->name('notices.store');
    Route::get('notices/edit/{notice}', [NoticeController::class, 'edit'])->name('notices.edit');
    Route::post('notices/update/{notice}', [NoticeController::class, 'update'])->name('notices.update');
    Route::get('notices/delete/{notice}', [NoticeController::class, 'destroy'])->name('notices.delete');

    //seo page



    Route::get('seo-pages/index', [SeoPageController::class, 'index'])->name('seo-pages.index');

    Route::get('seo-pages/create', [SeoPageController::class, 'create'])->name('seo-pages.create');

    Route::post('seo-pages/store', [SeoPageController::class, 'store'])->name('seo-pages.store');

    Route::get('seo-pages/edit/{seoPage}', [SeoPageController::class, 'edit'])->name('seo-pages.edit');

    Route::post('seo-pages/update/{seoPage}', [SeoPageController::class, 'update'])->name('seo-pages.update');

    Route::get('seo-pages/delete/{seoPage}', [SeoPageController::class, 'destroy'])->name('seo-pages.delete');
});

Route::middleware(['auth'])->group(function () {
    Route::livewire('invitations/{invitation}/accept', 'pages::teams.accept-invitation')
        ->name('invitations.accept');
});




require __DIR__ . '/settings.php';
