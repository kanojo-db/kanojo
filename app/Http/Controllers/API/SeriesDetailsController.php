<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Series;
use App\Transformers\SeriesTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Fractalistic\ArraySerializer;

class SeriesDetailsController extends Controller
{
    /**
     * Details
     *
     * Get the primary information about a series.
     *
     * @group Movie Series
     *
     * @urlParam series_id required The ID of the series.
     *
     * @queryParam language string Pass a locale value to display translated data for the fields that
     * support it. Defaults to en-US. Example: ja-JP
     */
    public function show(Request $request, string $series_id): JsonResponse
    {
        $validatedData = $request->validate([
            // TODO: Validate according to the list of languages we support at a given time.
            'language' => ['nullable', 'string'],
            'page' => ['nullable', 'integer'],
            'include' => ['nullable', 'string'],
        ]);

        $series = Series::where('id', $series_id)->firstOrFail();

        $includes = [];
        if (isset($validatedData['include'])) {
            $includes = explode(',', $validatedData['include']);
        }

        return response()->json(
            fractal()
                ->item($series)
                ->parseIncludes($includes)
                ->transformWith(new SeriesTransformer($validatedData['language'] ?? null))
                ->serializeWith(new ArraySerializer())
                ->toArray()
        );
    }
}
