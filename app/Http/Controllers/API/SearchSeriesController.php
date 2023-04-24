<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Series;
use App\Transformers\SeriesSearchResultTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class SearchSeriesController extends Controller
{
    /**
     * Search Series
     *
     * Search for movie series.
     *
     * @group Search
     *
     * @queryParam query string required Pass a text query to search. Example: ニーハイコレクション
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

        $paginator = Series::search($validatedData['query'])->paginate(25);

        $language = $validatedData['language'] ?? 'en-US';

        return response()->json(
            fractal()
                ->collection($paginator->getCollection())
                ->transformWith(new SeriesSearchResultTransformer($language))
                ->paginateWith(new IlluminatePaginatorAdapter($paginator))
                ->toArray()
        );
    }
}
