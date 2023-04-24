<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Transformers\MovieSearchResultTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class MoviePopularController extends Controller
{
    /**
     * Popular
     *
     * List the most popular movies.
     *
     * @group Movies
     *
     * @queryParam language string Pass a locale value to display translated data for the fields that support it. Defaults to en-US. Example: ja-JP
     * @queryParam page integer Specify which page to query. Defaults to 1. Example: 2
     */
    public function index(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            // TODO: Validate according to the list of languages we support at a given time.
            'language' => ['nullable', 'string'],
            'page' => ['nullable', 'integer'],
        ]);

        $paginator = Movie::popular()->paginate(25);

        $language = $validatedData['language'] ?? 'en-US';

        return response()->json(
            fractal()
                ->collection($paginator->getCollection())
                ->transformWith(new MovieSearchResultTransformer($language))
                ->paginateWith(new IlluminatePaginatorAdapter($paginator))
                ->toArray()
        );
    }
}
