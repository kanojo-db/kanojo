<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Transformers\MovieCreditsTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Fractalistic\ArraySerializer;

class MovieCreditsController extends Controller
{
    /**
     * Credits
     *
     * Get the credits for a movie.
     *
     * @group Movies
     *
     * @urlParam movie_id required The ID of the movie.
     *
     * @queryParam language string Pass a locale value to display translated data for the fields that support it. Defaults to en-US. Example: ja-JP
     */
    public function show(Request $request, string $product_code): JsonResponse
    {
        $movie = Movie::whereHas('versions', function ($query) use ($product_code) {
            $query->where('product_code', $product_code);
        })
            ->with('models')
            ->firstOrFail();

        return response()->json(
            fractal()
                ->item($movie)
                ->transformWith(new MovieCreditsTransformer())
                ->serializeWith(new ArraySerializer())
                ->toArray()
        );
    }
}
