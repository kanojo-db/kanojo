<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\Movie;
use App\Models\Person;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;
use Inertia\Response;

class SearchController extends Controller
{
    public function __invoke(SearchRequest $request): Response
    {
        $validatedData = $request->validated();

        return Inertia::render('Search', [
            'modelsResults' => function () use ($validatedData): LengthAwarePaginator {
                return Person::search($validatedData['q'])->query(function (Builder $query): Builder {
                    return $query->with('media');
                })
                ->paginate(25)
                ->appends(request()->query());
            },
            'moviesResults' => function () use ($validatedData): LengthAwarePaginator {
                return Movie::search($validatedData['q'])
                    ->paginate(25)
                    ->appends(request()->query());
            },
        ]);
    }
}
