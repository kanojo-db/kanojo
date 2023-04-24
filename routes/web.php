<?php

declare(strict_types=1);

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContentReportController;
use App\Http\Controllers\MovieCollectionController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\MovieDislikeController;
use App\Http\Controllers\MovieFavoriteController;
use App\Http\Controllers\MovieHistoryController;
use App\Http\Controllers\MovieLikeController;
use App\Http\Controllers\MovieMediaController;
use App\Http\Controllers\MovieMediaLikeController;
use App\Http\Controllers\MoviePreviewController;
use App\Http\Controllers\MovieWishlistController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\PersonHistoryController;
use App\Http\Controllers\PersonMediaController;
use App\Http\Controllers\PersonMediaDislikeController;
use App\Http\Controllers\PersonMediaLikeController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsAccountController;
use App\Http\Controllers\SettingsSessionsController;
use App\Http\Controllers\SettingsTokensController;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\StudioController;
use App\Http\Controllers\WelcomeController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

/**
 * General routes.
 */
Route::get('/', WelcomeController::class)->name('welcome');
Route::get('/privacy-policy', [PrivacyPolicyController::class, 'show'])->name('privacy');

/**
 * Social login routes.
 */
Route::get('/login/{provider}', [SocialLoginController::class, 'redirect'])->name('login.provider');
Route::get('/login/{provider}/callback', [SocialLoginController::class, 'callback'])->name('login.provider.callback');

/**
 * Marketing pages.
 */
Route::resource('about', AboutController::class)->only(['index'])->shallow();

/**
 * Protected general routes.
 */
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/settings/account', [SettingsAccountController::class, 'show'])->name('settings.account');
    Route::post('/settings/account', [SettingsAccountController::class, 'update'])->name('settings.account.update');

    Route::get('/settings/sessions', [SettingsSessionsController::class, 'show'])->name('settings.sessions');

    Route::get('/settings/tokens', [SettingsTokensController::class, 'index'])->name('settings.tokens');
    Route::post('/settings/tokens', [SettingsTokensController::class, 'store'])->name('settings.tokens.create');
    Route::delete('/settings/tokens/{token}', [SettingsTokensController::class, 'destroy'])->name('settings.tokens.destroy');
});

/**
 * Search routes.
 */
Route::get('search', SearchController::class)->name('search');

/**
 * Movie routes.
 */
Route::resource('movies', MovieController::class);
Route::resource('movies.media', MovieMediaController::class)->only([
    'index', 'store', 'destroy',
])->shallow();
Route::resource('movies.media.like', MovieMediaLikeController::class)->only(['store'])->shallow();
Route::resource('movies.media.dislike', MovieMediaLikeController::class)->only(['store'])->shallow();
Route::resource('movies.history', MovieHistoryController::class)->only([
    'index',
])->shallow();
Route::resource('movies.like', MovieLikeController::class)->only(['store'])->shallow();
Route::resource('movies.dislike', MovieDislikeController::class)->only(['store'])->shallow();
Route::resource('movies.favorite', MovieFavoriteController::class)->only(['store', 'destroy'])->shallow();
Route::resource('movies.wishlist', MovieWishlistController::class)->only(['store', 'destroy'])->shallow();
Route::resource('movies.collection', MovieCollectionController::class)->only(['store', 'destroy'])->shallow();
Route::resource('movies.reports', ContentReportController::class)->shallow();
Route::get('/movies/{movie}/preview', [MoviePreviewController::class, 'show'])->name('movies.preview.show');

/**
 * Person routes.
 */
Route::resource('models', PersonController::class);
Route::resource('models.media', PersonMediaController::class)->only([
    'index', 'store', 'destroy',
])->shallow();
Route::post('/models/{person}/media/{media}/like', [PersonMediaLikeController::class, 'store'])->name('models.media.like');
Route::post('/models/{person}/media/{media}/dislike', [PersonMediaDislikeController::class, 'store'])->name('models.media.dislike');
Route::resource('models.history', PersonHistoryController::class)->only([
    'index',
])->shallow();

/**
 * Studio routes.
 */
Route::resource('studios', StudioController::class);

/**
 * User profile routes.
 */
Route::get('/user/{user}', function (User $user) {
    /** @var User|null */
    $currentUser = Auth::user();

    return Inertia::render('Profile/Show', [
        'user' => $user,
        'isCurrentUser' => $user->is($currentUser),
        'editsCount' => \OwenIt\Auditing\Models\Audit::where('user_id', $user->id)->count(),
        'favoritesCount' => $user->favorites()->count(),
        'collectionCount' => $user->collection()->count(),
        'wishlistCount' => $user->wishlist()->count(),
    ]);
})->name('profile.show');

/**
 * Contribution bible routes.
 */
Route::get('bible', function () {
    return Inertia::render('Bible/Index');
})->name('bible.index');
Route::get('bible/general', function () {
    return Inertia::render('Bible/General');
})->name('bible.general');

/**
 * User locale switching.
 */
Route::post('/user/locale', function () {
    request()->validate([
        'locale' => ['required', 'string', 'in:en-US,fr-FR,es-ES'],
    ]);

    app()->setLocale(request('locale'));
    session()->put('locale', request('locale'));

    return redirect()->back();
})->name('user.locale.store');
