<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;

class SearchMovieController extends Controller
{
    /**
     * Search Movies
     *
     * Search for movies.
     *
     * @group Search
     * @queryParam query string required Pass a text query to search. Can be a title or a product code. Example: ABC-123
     * @queryParam language string Pass a locale value to display translated data for the fields that support it. Defaults to en. Example: jp
     * @queryParam page integer Specify which page to query. Defaults to 1. Example: 2
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $search_query = request()->query('query');

        // If there is no query, the request is invalid.
        if (empty($search_query)) {
            return response()->json([
                'error' => 'Invalid request.',
            ], 400);
        }

        $movies_results = Movie::search($search_query)->paginate(25);

        return response()->json(
            $movies_results
        );
    }
}
