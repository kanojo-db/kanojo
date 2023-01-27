<?php

declare(strict_types=1);

use App\Http\Controllers\API\MovieDetailsController;
use App\Http\Controllers\API\SearchMovieController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */
// middleware('auth:sanctum')->
Route::get('/search/movie', [SearchMovieController::class, 'index']);
Route::get('/movie/{movie}', [MovieDetailsController::class, 'show']);
