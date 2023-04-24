<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Transformers\PersonTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Fractalistic\ArraySerializer;

class PersonDetailsController extends Controller
{
    /**
     * Details
     *
     * Get the primary information about a person.
     *
     * @group People
     *
     * @urlParam person_id required The ID of the person.
     *
     * @queryParam language string Pass a locale value to display translated data for the fields that
     * support it. Defaults to en-US. Example: ja-JP
     */
    public function show(Request $request, string $person_id): JsonResponse
    {
        $validatedData = $request->validate([
            // TODO: Validate according to the list of languages we support at a given time.
            'language' => ['nullable', 'string'],
            'page' => ['nullable', 'integer'],
            'include' => ['nullable', 'string'],
        ]);

        $person = Person::where('id', $person_id)->firstOrFail();

        $includes = [];
        if (isset($validatedData['include'])) {
            $includes = explode(',', $validatedData['include']);
        }

        return response()->json(
            fractal()
                ->item($person)
                ->parseIncludes($includes)
                ->transformWith(new PersonTransformer($validatedData['language'] ?? null))
                ->serializeWith(new ArraySerializer())
                ->toArray()
        );
    }
}
