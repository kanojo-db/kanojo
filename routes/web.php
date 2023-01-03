<?php

use App\Http\Controllers\AboutKanojo;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\MovieHistoryController;
use App\Http\Controllers\MovieLikeController;
use App\Http\Controllers\MovieDislikeController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\PersonHistoryController;
use App\Http\Controllers\PersonMediaController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsAccountController;
use App\Models\Movie;
use App\Models\Person;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'popularModels' => Person::with(['media'])->popularMonth()->take(25)->get(),
        'popularMovies' => QueryBuilder::for(Movie::class)
            ->defaultSort('-created_at')
            ->allowedSorts('created_at')
            ->allowedFilters([
                AllowedFilter::scope('filter_hidden')->default(true),
            ])
            ->with([
                'media',
                'loveReactant.reactions.reacter.reacterable',
                'loveReactant.reactions.type',
                'loveReactant.reactionCounters',
                'loveReactant.reactionTotal'
            ])
            ->popularMonth()
            ->take(25)
            ->get(),
        'latestMovies' => QueryBuilder::for(Movie::class)
            ->defaultSort('-created_at')
            ->allowedSorts('created_at')
            ->allowedFilters([
                AllowedFilter::scope('filter_hidden')->default(true),
            ])
            ->with([
                'media',
                'loveReactant.reactions.reacter.reacterable',
                'loveReactant.reactions.type',
                'loveReactant.reactionCounters',
                'loveReactant.reactionTotal'
            ])
            ->latest()
            ->take(25)
            ->get(),
        'recentlyUpdatedMovies' => QueryBuilder::for(Movie::class)
            ->defaultSort('-updated_at')
            ->allowedSorts('updated_at')
            ->allowedFilters([
                AllowedFilter::scope('filter_hidden')->default(true),
            ])
            ->with([
                'media',
                'loveReactant.reactions.reacter.reacterable',
                'loveReactant.reactions.type',
                'loveReactant.reactionCounters',
                'loveReactant.reactionTotal'
            ])
            ->take(25)
            ->get(),
        'recentlyReleasedMovies' => QueryBuilder::for(Movie::class)
            ->defaultSort('-release_date')
            ->allowedSorts('release_date')
            ->allowedFilters([
                AllowedFilter::scope('filter_hidden')->default(true),
            ])
            ->where('release_date', '<=', now()->toDateString())
            ->with([
                'media',
                'loveReactant.reactions.reacter.reacterable',
                'loveReactant.reactions.type',
                'loveReactant.reactionCounters',
                'loveReactant.reactionTotal'
            ])
            ->take(25)
            ->get(),
        'movieCount' => Movie::count(),
        'modelCount' => Person::count(),
        'tagCount' => \Spatie\Tags\Tag::count(),
    ]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/settings/account', [SettingsAccountController::class, 'show'])->name('settings.account');
    Route::post('/settings/account', [SettingsAccountController::class, 'update'])->name('settings.account.update');
});

Route::get('search', SearchController::class)->name('search');

Route::resource('about', AboutKanojo::class)->only(['index'])->shallow();

Route::resource('movies', MovieController::class);
/*Route::resource('movies.media', MovieMediaController::class)->only([
    'index', 'store', 'destroy'
])->shallow();*/
Route::resource('movies.history', MovieHistoryController::class)->only([
    'index'
])->shallow();
Route::resource('movies.like', MovieLikeController::class)->only(['store'])->shallow();
Route::resource('movies.dislike', MovieDislikeController::class)->only(['store'])->shallow();

Route::resource('models', PersonController::class);
Route::resource('models.media', PersonMediaController::class)->only([
    'index', 'store', 'destroy'
])->shallow();
Route::resource('models.history', PersonHistoryController::class)->only([
    'index'
])->shallow();

Route::get('bible/general', function () {
    return Inertia::render('Bible/General');
})->name('bible.general');
