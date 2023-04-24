<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Transformers\MovieMediaTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Fractalistic\ArraySerializer;

class MovieMediaController extends Controller
{
    /**
     * Images
     *
     * Get the images that belong to a movie.
     *
     * @group Movies
     *
     * @urlParam movie_id required The ID of the movie.
     */
    public function show(Request $request, string $movie_id): JsonResponse
    {
        $movie = Movie::where('id', $movie_id)
            ->with('media')
            ->firstOrFail();

        return response()->json(
            fractal()
                ->item($movie)
                ->transformWith(new MovieMediaTransformer())
                ->serializeWith(new ArraySerializer())
                ->toArray()
        );
    }
}
