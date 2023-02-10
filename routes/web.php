<?php

declare(strict_types=1);

use App\Http\Controllers\AboutKanojo;
use App\Http\Controllers\ContentReportController;
use App\Http\Controllers\MovieCollectionController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\MovieDislikeController;
use App\Http\Controllers\MovieFavoriteController;
use App\Http\Controllers\MovieHistoryController;
use App\Http\Controllers\MovieLikeController;
use App\Http\Controllers\MovieMediaController;
use App\Http\Controllers\MovieWishlistController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\PersonHistoryController;
use App\Http\Controllers\PersonMediaController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsAccountController;
use App\Http\Controllers\SettingsSessionsController;
use App\Http\Controllers\SettingsTokensController;
use App\Http\Controllers\StudioController;
use App\Models\Movie;
use App\Models\Person;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'popularModels' => Person::with(['media'])->popularMonth()->take(25)->get(),
        'popularMovies' => QueryBuilder::for(Movie::class)
            ->defaultSort('-created_at')
            ->allowedSorts('created_at')
            ->with([
                'media',
                'loveReactant.reactions.reacter.reacterable',
                'loveReactant.reactions.type',
                'loveReactant.reactionCounters',
                'loveReactant.reactionTotal',
                'type',
            ])
            ->popularMonth()
            ->take(25)
            ->get(),
        'latestMovies' => QueryBuilder::for(Movie::class)
            ->defaultSort('-created_at')
            ->allowedSorts('created_at')
            ->with([
                'media',
                'loveReactant.reactions.reacter.reacterable',
                'loveReactant.reactions.type',
                'loveReactant.reactionCounters',
                'loveReactant.reactionTotal',
                'type',
            ])
            ->latest()
            ->take(25)
            ->get(),
        'recentlyUpdatedMovies' => QueryBuilder::for(Movie::class)
            ->defaultSort('-updated_at')
            ->allowedSorts('updated_at')
            ->with([
                'media',
                'loveReactant.reactions.reacter.reacterable',
                'loveReactant.reactions.type',
                'loveReactant.reactionCounters',
                'loveReactant.reactionTotal',
                'type',
            ])
            ->take(25)
            ->get(),
        'recentlyReleasedMovies' => QueryBuilder::for(Movie::class)
            ->defaultSort('-release_date')
            ->allowedSorts('release_date')
            ->where('release_date', '<=', now()->toDateString())
            ->with([
                'media',
                'loveReactant.reactions.reacter.reacterable',
                'loveReactant.reactions.type',
                'loveReactant.reactionCounters',
                'loveReactant.reactionTotal',
                'type',
            ])
            ->take(25)
            ->get(),
        'movieCount' => Movie::withoutGlobalScope('filterHidden')->count(),
        'modelCount' => Person::count(),
        'tagCount' => \Spatie\Tags\Tag::count(),
        'topUsers' => User::with(['audits'])->withCount(['audits'])->take(10)->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
                'total_audits' => $user->audits_count,
                'audits_this_week' => $user->audits()->where('created_at', '>=', now()->subWeek())->count(),
            ];
        }),
    ]);
});

Route::post('/user/locale', function () {
    request()->validate([
        'locale' => ['required', 'string', 'in:en-US,fr-FR,es-ES'],
    ]);

    app()->setLocale(request('locale'));
    session()->put('locale', request('locale'));

    return redirect()->back();
})->name('user.locale.store');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/settings/account', [SettingsAccountController::class, 'show'])->name('settings.account');
    Route::post('/settings/account', [SettingsAccountController::class, 'update'])->name('settings.account.update');

    Route::get('/settings/sessions', [SettingsSessionsController::class, 'show'])->name('settings.sessions');

    Route::get('/settings/tokens', [SettingsTokensController::class, 'index'])->name('settings.tokens');
    Route::post('/settings/tokens', [SettingsTokensController::class, 'store'])->name('settings.tokens.create');
    Route::delete('/settings/tokens/{token}', [SettingsTokensController::class, 'destroy'])->name('settings.tokens.destroy');
});

Route::get('search', SearchController::class)->name('search');

Route::resource('about', AboutKanojo::class)->only(['index'])->shallow();

Route::resource('movies', MovieController::class);
Route::resource('movies.media', MovieMediaController::class)->only([
    'index', 'store', 'destroy',
])->shallow();
Route::resource('movies.history', MovieHistoryController::class)->only([
    'index',
])->shallow();
Route::resource('movies.like', MovieLikeController::class)->only(['store'])->shallow();
Route::resource('movies.dislike', MovieDislikeController::class)->only(['store'])->shallow();
Route::resource('movies.favorite', MovieFavoriteController::class)->only(['store', 'destroy'])->shallow();
Route::resource('movies.wishlist', MovieWishlistController::class)->only(['store', 'destroy'])->shallow();
Route::resource('movies.collection', MovieCollectionController::class)->only(['store', 'destroy'])->shallow();
Route::resource('movies.reports', ContentReportController::class)->shallow();

Route::resource('models', PersonController::class);
Route::resource('models.media', PersonMediaController::class)->only([
    'index', 'store', 'destroy',
])->shallow();
Route::resource('models.history', PersonHistoryController::class)->only([
    'index',
])->shallow();

Route::resource('studios', StudioController::class);

Route::get('/user/{user}', function (User $user) {
    return Inertia::render('Profile/Show', [
        'user' => $user,
        'isCurrentUser' => $user->is(auth()->user()),
        'editsCount' => \OwenIt\Auditing\Models\Audit::where('user_id', $user->id)->count(),
        'favoritesCount' => $user->favorites()->count(),
        'collectionCount' => $user->collection()->count(),
        'wishlistCount' => $user->wishlist()->count(),
    ]);
})->name('profile.show');

Route::get('bible/general', function () {
    return Inertia::render('Bible/General');
})->name('bible.general');
