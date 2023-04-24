<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Series;
use App\Transformers\SeriesMediaTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Fractalistic\ArraySerializer;

class SeriesMediaController extends Controller
{
    /**
     * Images
     *
     * Get the images that belong to a series.
     *
     * @group Series
     *
     * @urlParam series_id required The ID of the series.
     */
    public function show(Request $request, string $series_id): JsonResponse
    {
        $series = Series::where('id', $series_id)
            ->with('media')
            ->firstOrFail();

        return response()->json(
            fractal()
                ->item($series)
                ->transformWith(new SeriesMediaTransformer())
                ->serializeWith(new ArraySerializer())
                ->toArray()
        );
    }
}
