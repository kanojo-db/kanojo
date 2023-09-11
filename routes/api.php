<?php

declare(strict_types=1);

use App\Http\Controllers\API\ChangesMoviesController;
use App\Http\Controllers\API\ChangesPeopleController;
use App\Http\Controllers\API\ChangesSeriesController;
use App\Http\Controllers\API\ChangesStudioController;
use App\Http\Controllers\API\MovieCreditsController;
use App\Http\Controllers\API\MovieDetailsController;
use App\Http\Controllers\API\MovieMediaController;
use App\Http\Controllers\API\MoviePopularController;
use App\Http\Controllers\API\MovieRecentlyReleasedController;
use App\Http\Controllers\API\PersonDetailsController;
use App\Http\Controllers\API\PersonMediaController;
use App\Http\Controllers\API\PersonMoviesController;
use App\Http\Controllers\API\PersonPopularController;
use App\Http\Controllers\API\SearchMovieController;
use App\Http\Controllers\API\SearchPersonController;
use App\Http\Controllers\API\SearchSeriesController;
use App\Http\Controllers\API\SearchStudioController;
use App\Http\Controllers\API\SeriesDetailsController;
use App\Http\Controllers\API\SeriesMediaController;
use App\Http\Controllers\API\SeriesMoviesController;
use App\Http\Controllers\API\StudioDetailsController;
use App\Http\Controllers\API\StudioMediaController;
use App\Http\Controllers\API\StudioMoviesController;
use App\Http\Controllers\API\UserCollectionController;
use App\Http\Controllers\API\UserDetailsController;
use App\Http\Controllers\API\UserFavoritesController;
use App\Http\Controllers\API\UserWishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */
Route::middleware('auth:sanctum')->group(function () {
    /**
     * Account
     */
    Route::get('/account/{account_id}', [UserDetailsController::class, 'show'])->where('account_id', '\d+');
    Route::get('/account/{account_id}/favorite/movies', [UserFavoritesController::class, 'show'])->where('account_id', '\d+');
    Route::get('/account/{account_id}/wishlist/movies', [UserWishlistController::class, 'show'])->where('account_id', '\d+');
    Route::get('/account/{account_id}/collection/movies', [UserCollectionController::class, 'show'])->where('account_id', '\d+');

    /**
     * Changes
     */
    Route::get('/movie/changes', [ChangesMoviesController::class, 'index']);
    Route::get('/person/changes', [ChangesPeopleController::class, 'index']);
    Route::get('/studio/changes', [ChangesStudioController::class, 'index']);
    Route::get('/series/changes', [ChangesSeriesController::class, 'index']);

    /**
     * Search
     */
    Route::get('/search/movie', [SearchMovieController::class, 'index']);
    Route::get('/search/person', [SearchPersonController::class, 'index']);
    Route::get('/search/studio', [SearchStudioController::class, 'index']);
    Route::get('/search/series', [SearchSeriesController::class, 'index']);

    /**
     * Movie
     */
    Route::get('/movie/popular', [MoviePopularController::class, 'index']);
    Route::get('/movie/recently-released', [MovieRecentlyReleasedController::class, 'index']);
    Route::get('/movie/{movie_id}', [MovieDetailsController::class, 'show'])->where('movie_id', '\d+');
    Route::get('/movie/{movie_id}/credits', [MovieCreditsController::class, 'show'])->where('movie_id', '\d+');
    Route::get('/movie/{movie_id}/images', [MovieMediaController::class, 'show'])->where('movie_id', '\d+');

    /**
     * Person
     */
    Route::get('/person/popular', [PersonPopularController::class, 'index']);
    Route::get('/person/{person_id}', [PersonDetailsController::class, 'show'])->where('person_id', '\d+');
    Route::get('/person/{person_id}/images', [PersonMediaController::class, 'show'])->where('person_id', '\d+');
    Route::get('/person/{person_id}/movies', [PersonMoviesController::class, 'show'])->where('person_id', '\d+');

    /**
     * Studio
     */
    Route::get('/studio/{studio_id}', [StudioDetailsController::class, 'show'])->where('studio_id', '\d+');
    Route::get('/studio/{studio_id}/images', [StudioMediaController::class, 'show'])->where('studio_id', '\d+');
    Route::get('/studio/{studio_id}/movies', [StudioMoviesController::class, 'show'])->where('studio_id', '\d+');

    /**
     * Series
     */
    Route::get('/series/{series_id}', [SeriesDetailsController::class, 'show'])->where('series_id', '\d+');
    Route::get('/series/{series_id}/images', [SeriesMediaController::class, 'show'])->where('series_id', '\d+');
    Route::get('/series/{series_id}/movies', [SeriesMoviesController::class, 'show'])->where('series_id', '\d+');
});
