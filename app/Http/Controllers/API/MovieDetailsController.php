<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Transformers\MovieTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Fractalistic\ArraySerializer;

class MovieDetailsController extends Controller
{
    /**
     * Details
     *
     * Get the primary information about a movie.
     *
     * @group Movies
     *
     * @urlParam movie_id required The ID of the movie.
     *
     * @queryParam language string Pass a locale value to display translated data for the fields that
     * support it. Defaults to en-US. Example: ja-JP
     */
    public function show(Request $request, string $movie_id): JsonResponse
    {
        $validatedData = $request->validate([
            // TODO: Validate according to the list of languages we support at a given time.
            'language' => ['nullable', 'string'],
            'page' => ['nullable', 'integer'],
        ]);

        $movie = Movie::where('id', $movie_id)->firstOrFail();

        return response()->json(
            fractal()
                ->item($movie)
                ->parseIncludes(['cast', 'studio'])
                ->transformWith(new MovieTransformer($validatedData['language'] ?? null))
                ->serializeWith(new ArraySerializer())
                ->toArray()
        );
    }
}
