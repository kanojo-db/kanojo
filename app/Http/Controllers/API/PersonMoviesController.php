<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Transformers\MovieSearchResultTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Fractalistic\ArraySerializer;

class PersonMoviesController extends Controller
{
    /**
     * Movies
     *
     * Get the movies in which a person is featured.
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
        ]);

        $person = Person::where('id', $person_id)->firstOrFail();

        $paginator = $person->movies()->latest('release_date')->paginate(25);

        return response()->json(
            fractal()
                ->collection($paginator->getCollection())
                ->transformWith(new MovieSearchResultTransformer($validatedData['language'] ?? null))
                ->serializeWith(new ArraySerializer())
                ->toArray()
        );
    }
}
