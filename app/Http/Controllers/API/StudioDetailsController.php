<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Studio;
use App\Transformers\StudioTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Fractalistic\ArraySerializer;

class StudioDetailsController extends Controller
{
    /**
     * Details
     *
     * Get the primary information about a studio.
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
            'include' => ['nullable', 'string'],
        ]);

        $studio = Studio::where('id', $studio_id)->firstOrFail();

        $includes = [];
        if (isset($validatedData['include'])) {
            $includes = explode(',', $validatedData['include']);
        }

        return response()->json(
            fractal()
                ->item($studio)
                ->parseIncludes($includes)
                ->transformWith(new StudioTransformer($validatedData['language'] ?? null))
                ->serializeWith(new ArraySerializer())
                ->toArray()
        );
    }
}
