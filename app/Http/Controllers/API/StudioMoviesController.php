<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Studio;
use App\Transformers\MovieSearchResultTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Fractalistic\ArraySerializer;

class StudioMoviesController extends Controller
{
    /**
     * Movies
     *
     * Get the movies from a studio.
     *
     * @group Studios
     *
     * @urlParam studio_id required The ID of the studio.
     *
     * @queryParam language string Pass a locale value to display translated data for the fields that
     * support it. Defaults to en-US. Example: ja-JP
     */
    public function show(Request $request, string $studio_id): JsonResponse
    {
        $validatedData = $request->validate([
            // TODO: Validate according to the list of languages we support at a given time.
            'language' => ['nullable', 'string'],
            'page' => ['nullable', 'integer'],
        ]);

        $studio = Studio::where('id', $studio_id)->firstOrFail();

        $paginator = $studio->movies()->latest('release_date')->paginate(25);

        return response()->json(
            fractal()
                ->collection($paginator->getCollection())
                ->transformWith(new MovieSearchResultTransformer($validatedData['language'] ?? null))
                ->serializeWith(new ArraySerializer())
                ->toArray()
        );
    }
}
