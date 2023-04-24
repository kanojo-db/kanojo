<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Studio;
use App\Transformers\StudioMediaTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Fractalistic\ArraySerializer;

class StudioMediaController extends Controller
{
    /**
     * Images
     *
     * Get the images that belong to a studio.
     *
     * @group Studios
     *
     * @urlParam studio_id required The ID of the studio.
     */
    public function show(Request $request, string $studio_id): JsonResponse
    {
        $studio = Studio::where('id', $studio_id)
            ->with('media')
            ->firstOrFail();

        return response()->json(
            fractal()
                ->item($studio)
                ->transformWith(new StudioMediaTransformer())
                ->serializeWith(new ArraySerializer())
                ->toArray()
        );
    }
}
