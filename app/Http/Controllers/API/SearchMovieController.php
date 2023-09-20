<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MovieVersion;
use App\Transformers\MovieVersionSearchResultTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class SearchMovieController extends Controller
{
    /**
     * Search Movies
     *
     * Search for movies.
     *
     * @group Search
     *
     * @queryParam query string required Pass a text query to search. Can be a title or a product code. Example: ABC-123
     * @queryParam language string Pass a locale value to display translated data for the fields that support it. Defaults to en-US. Example: ja-JP
     * @queryParam page integer Specify which page to query. Defaults to 1. Example: 2
     */
    public function index(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'query' => ['required', 'string'],
            // TODO: Validate according to the list of languages we support at a given time.
            'language' => ['nullable', 'string'],
            'page' => ['nullable', 'integer'],
        ]);

        /** @var \Illuminate\Pagination\LengthAwarePaginator<MovieVersion> $paginator */
        $paginator = MovieVersion::search($validatedData['query'])->paginate(25);

        $language = $validatedData['language'] ?? 'en-US';

        return response()->json(
            fractal()
                ->collection($paginator->getCollection())
                ->transformWith(new MovieVersionSearchResultTransformer($language))
                ->paginateWith(new IlluminatePaginatorAdapter($paginator))
                ->toArray()
        );
    }
}
