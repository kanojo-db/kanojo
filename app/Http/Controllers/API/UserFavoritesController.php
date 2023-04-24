<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Transformers\MovieSearchResultTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class UserFavoritesController extends Controller
{
    /**
     * Favorites
     *
     * List the movies favorited by a user.
     *
     * @group Account
     *
     * @urlParam account_id required The ID of the account.
     *
     * @queryParam language string Pass a locale value to display translated data for the fields that support it. Defaults to en-US. Example: ja-JP
     * @queryParam page integer Specify which page to query. Defaults to 1. Example: 2
     */
    public function show(Request $request, string $account_id): JsonResponse
    {
        $validatedData = $request->validate([
            // TODO: Validate according to the list of languages we support at a given time.
            'language' => ['nullable', 'string'],
            'page' => ['nullable', 'integer'],
        ]);

        $user = User::where('id', $account_id)
            ->firstOrFail();

        // Order the favorites by the date they were favorited.
        $paginator = $user->favorites()->withPivot('created_at')->orderBy('pivot_created_at', 'desc')->paginate(25);

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
