<?php

declare(strict_types=1);

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AgeGateController;
use App\Http\Controllers\MediaServerPluginController;
use App\Http\Controllers\MovieCastController;
use App\Http\Controllers\MovieCollectionController;
use App\Http\Controllers\MovieContentReportController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\MovieDislikeController;
use App\Http\Controllers\MovieExternalIdsController;
use App\Http\Controllers\MovieFavoriteController;
use App\Http\Controllers\MovieHistoryController;
use App\Http\Controllers\MovieLikeController;
use App\Http\Controllers\MovieMediaController;
use App\Http\Controllers\MovieMediaLikeController;
use App\Http\Controllers\MoviePreviewInternalController;
use App\Http\Controllers\MovieVersionController;
use App\Http\Controllers\MovieWishlistController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PersonAliasController;
use App\Http\Controllers\PersonContentReportController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\PersonExternalIdsController;
use App\Http\Controllers\PersonHistoryController;
use App\Http\Controllers\PersonMediaController;
use App\Http\Controllers\PersonMediaDislikeController;
use App\Http\Controllers\PersonMediaLikeController;
use App\Http\Controllers\PersonPreviewInternalController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SeriesContentReportController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\SeriesHistoryController;
use App\Http\Controllers\SettingsAccountController;
use App\Http\Controllers\SettingsSessionsController;
use App\Http\Controllers\SettingsTokensController;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\StudioContentReportController;
use App\Http\Controllers\StudioController;
use App\Http\Controllers\StudioExternalIdsController;
use App\Http\Controllers\StudioHistoryController;
use App\Http\Controllers\StudioMediaController;
use App\Http\Controllers\WelcomeController;
use App\Models\Movie;
use App\Models\Person;
use App\Models\Series;
use App\Models\Studio;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use OwenIt\Auditing\Models\Audit;

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
Route::get('/', WelcomeController::class)
    ->name('welcome');
Route::get('/privacy-policy', [PrivacyPolicyController::class, 'show'])
    ->name('privacy');
Route::get('/age-check', [AgeGateController::class, 'index'])
    ->name('age_gate.index');
Route::post('/age-check', [AgeGateController::class, 'store'])
    ->name('age_gate.store');
Route::get('/plugins', MediaServerPluginController::class)
    ->name('plugins');

/**
 * Social login routes.
 */
// Route::get('/login/{provider}', [SocialLoginController::class, 'redirect'])
//    ->name('login.provider');
// Route::get('/login/{provider}/callback', [SocialLoginController::class, 'callback'])
//    ->name('login.provider.callback');

/**
 * Marketing pages.
 */
Route::resource('about', AboutController::class)
    ->only(['index']);

/**
 * Protected general routes.
 */
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/settings/account', [SettingsAccountController::class, 'show'])
        ->name('settings.account');
    Route::post('/settings/account', [SettingsAccountController::class, 'update'])
        ->name('settings.account.update');

    Route::get('/settings/sessions', [SettingsSessionsController::class, 'show'])
        ->name('settings.sessions');

    Route::get('/settings/tokens', [SettingsTokensController::class, 'index'])
        ->name('settings.tokens');
    Route::post('/settings/tokens', [SettingsTokensController::class, 'store'])
        ->name('settings.tokens.create');
    Route::delete('/settings/tokens/{token}', [SettingsTokensController::class, 'destroy'])
        ->name('settings.tokens.destroy');

    Route::resource('notifications', NotificationController::class)
        ->only(['index', 'update', 'destroy']);
});

/**
 * Search routes.
 */
Route::get('search', SearchController::class)
    ->name('search');

/**
 * Movie routes.
 */
// Redirect /movies/{id} to /movies/{slug}. {id} contains only numbers.
Route::get('/movies/{id}', function (string $id) {
    $movie = Movie::findOrFail($id);

    return redirect()->route('movies.show', $movie->slug);
})->where('id', '[0-9]+');
Route::resource('movies', MovieController::class);
Route::post('/movies/{movie}/cast', [MovieCastController::class, 'store'])
    ->name('movies.cast.store');
Route::put('/movies/{movie}/cast/{model}', [MovieCastController::class, 'update'])
    ->name('movies.cast.update');
Route::delete('/movies/{movie}/cast/{model}', [MovieCastController::class, 'destroy'])
    ->name('movies.cast.destroy');
Route::resource('movies.version', MovieVersionController::class)
    ->only(['store', 'update', 'destroy']);
Route::get('/movies/{movie}/media', [MovieMediaController::class, 'index'])
    ->name('movies.media.index');
Route::post('/movies/{movie}/media', [MovieMediaController::class, 'store'])
    ->name('movies.media.store');
Route::delete('/movies/{movie}/media/{media}', [MovieMediaController::class, 'destroy'])
    ->name('movies.media.destroy');
Route::resource('movies.media.like', MovieMediaLikeController::class)
    ->only(['store']);
Route::resource('movies.media.dislike', MovieMediaLikeController::class)
    ->only(['store']);
Route::post('/movies/{movie}/external-ids', [MovieExternalIdsController::class, '__invoke'])
    ->name('movies.external-ids.update');
Route::resource('movies.history', MovieHistoryController::class)
    ->only([
        'index',
    ]);
Route::resource('movies.like', MovieLikeController::class)
    ->only(['store']);
Route::resource('movies.dislike', MovieDislikeController::class)
    ->only(['store'])->shallow();
Route::resource('movies.favorite', MovieFavoriteController::class)
    ->only(['store', 'destroy'])->shallow();
Route::resource('movies.wishlist', MovieWishlistController::class)
    ->only(['store', 'destroy'])->shallow();
Route::resource('movies.collection', MovieCollectionController::class)
    ->only(['store', 'destroy'])->shallow();
Route::resource('movies.reports', MovieContentReportController::class);
Route::get('/movies/{movie}/preview', [MoviePreviewInternalController::class, 'show'])
    ->name('movies.preview.internal');

/**
 * Person routes.
 */
Route::get('/models/{id}', function (string $id) {
    $model = Person::findOrFail($id);

    return redirect()->route('models.show', $model->slug);
})->where('id', '[0-9]+');
Route::resource('models', PersonController::class);
Route::get('/models/{model}/media', [PersonMediaController::class, 'index'])
    ->name('models.media.index');
Route::post('/models/{model}/media', [PersonMediaController::class, 'store'])
    ->name('models.media.store');
Route::delete('/models/{model}/media/{media}', [PersonMediaController::class, 'destroy'])
    ->name('models.media.destroy');
Route::post('/models/{model}/media/{media}/like', [PersonMediaLikeController::class, 'store'])
    ->name('models.media.like');
Route::post('/models/{model}/media/{media}/dislike', [PersonMediaDislikeController::class, 'store'])
    ->name('models.media.dislike');
Route::post('/models/{model}/external-ids', [PersonExternalIdsController::class, '__invoke'])
    ->name('models.external-ids.update');
Route::resource('models.history', PersonHistoryController::class)
    ->only([
        'index',
    ]);
Route::resource('models.reports', PersonContentReportController::class);
Route::resource('models.alternative-names', PersonAliasController::class)
    ->only(['store', 'update', 'destroy']);
Route::get('/models/{model}/preview', [PersonPreviewInternalController::class, 'show'])
    ->name('models.preview.internal');

/**
 * Studio routes.
 */
Route::get('/studios/{id}', function (string $id) {
    $studio = Studio::findOrFail($id);

    return redirect()->route('studios.show', $studio->slug);
})->where('id', '[0-9]+');
Route::resource('studios', StudioController::class);
Route::get('/studios/{studio}/media', [StudioMediaController::class, 'index'])
    ->name('studios.media.index');
Route::post('/studios/{studio}/media', [StudioMediaController::class, 'store'])
    ->name('studios.media.store');
Route::delete('/studios/{studio}/media/{media}', [StudioMediaController::class, 'destroy'])
    ->name('studios.media.destroy');
Route::resource('studios.history', StudioHistoryController::class)
    ->only([
        'index',
    ]);
Route::resource('studios.reports', StudioContentReportController::class);
Route::post('/studios/{studio}/external-ids', [StudioExternalIdsController::class, '__invoke'])
    ->name('studios.external-ids.update');

/**
 * Series routes.
 */
Route::get('/series/{id}', function (string $id) {
    $series = Series::findOrFail($id);

    return redirect()->route('series.show', $series->slug);
})->where('id', '[0-9]+');
Route::resource('series', SeriesController::class);
Route::resource('series.history', SeriesHistoryController::class)
    ->only([
        'index',
    ]);
Route::resource('series.reports', SeriesContentReportController::class);

/**
 * User profile routes.
 */
Route::get('/user/{user}', function (User $user) {
    // Get active_tab query parameter
    $activeTab = request()->query('active_tab', 'activity');

    return Inertia::render('Profile/Show', [
        'userProfile' => $user,
        'editsCount' => Audit::where('user_id', $user->id)->count(),
        'averageRating' => $user->average_rating,
        'wishlistCount' => $user->wishlist()->count(),
        'favoriteCount' => $user->favorites()->count(),
        'collectionCount' => $user->collection()->count(),
        'activityThisPastMonth' => function () use ($user) {
            return Audit::where('user_id', $user->id)
                ->where('created_at', '>=', now()->subMonth())
                ->get()
                ->groupBy('auditable_type')
                ->map(function ($audits, $auditableType) {
                    return $audits->groupBy(function ($audit) {
                        return $audit->created_at->format('Y-m-d');
                    })->map(function ($audits, $date) {
                        return $audits->count();
                    })->toArray();
                });
        },
        'recentActivity' => function () use ($user) {
            return Audit::where('user_id', $user->id)
                ->where('event', '!=', 'deleted')
                ->with('auditable')
                ->latest()
                ->take(10)
                ->get();
        },
        'items' => function () use ($activeTab, $user) {
            switch ($activeTab) {
                case 'wishlist':
                    return $user->wishlist()->with('media')->latest()->paginate(20);
                case 'favorites':
                    return $user->favorites()->with('media')->latest()->paginate(20);
                case 'collection':
                    return $user->collection()->with('media')->latest()->paginate(20);
                default:
                    return null;
            }
        },
    ]);
})->name('profile.show');

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
