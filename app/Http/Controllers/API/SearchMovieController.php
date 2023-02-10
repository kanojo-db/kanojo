<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchMovieController extends Controller
{
    /**
     * Search Movies
     *
     * Search for movies.
     *
     * @group Search
     *
     * @queryParam query string required Pass a text query to search. Can be a title or a product code. Example: ABC-123
     * @queryParam language string Pass a locale value to display translated data for the fields that support it. Defaults to en. Example: jp
     * @queryParam page integer Specify which page to query. Defaults to 1. Example: 2
     */
    public function index(Request $request): JsonResponse
    {
        $searchQuery = request()->query('query');

        // If there is no query, the request is invalid.
        if (empty($searchQuery)) {
            return response()->json([
                'error' => 'Invalid request.',
            ], 400);
        }

        $moviesResults = Movie::search($searchQuery)->paginate(25);

        return response()->json(
            $moviesResults
        );
    }
}
