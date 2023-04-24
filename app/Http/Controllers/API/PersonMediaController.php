<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Transformers\PersonMediaTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Fractalistic\ArraySerializer;

class PersonMediaController extends Controller
{
    /**
     * Images
     *
     * Get the images that belong to a person.
     *
     * @group People
     *
     * @urlParam person_id required The ID of the person.
     */
    public function show(Request $request, string $person_id): JsonResponse
    {
        $movie = Person::where('id', $person_id)
            ->with('media')
            ->firstOrFail();

        return response()->json(
            fractal()
                ->item($movie)
                ->transformWith(new PersonMediaTransformer())
                ->serializeWith(new ArraySerializer())
                ->toArray()
        );
    }
}
