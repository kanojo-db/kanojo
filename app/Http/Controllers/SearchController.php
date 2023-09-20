<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\Movie;
use App\Models\Person;
use App\Models\Series;
use App\Models\Studio;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;
use Inertia\Response;

class SearchController extends Controller
{
    public function __invoke(SearchRequest $request): Response
    {
        /** @var array{q: string, type: ?string} */
        $validatedData = $request->validated();

        // If $validatedData['type'] is not set, then we'll default to 'movies'
        if (! isset($validatedData['type'])) {
            $validatedData['type'] = 'movies';
        }

        $personCount = Person::search($validatedData['q'])->count();
        $movieCount = Movie::search($validatedData['q'])->count();
        $seriesCount = Series::search($validatedData['q'])->count();
        $studioCount = Studio::search($validatedData['q'])->count();

        return Inertia::render('Search', [
            'items' => function () use ($validatedData): LengthAwarePaginator {
                if ($validatedData['type'] === 'people') {
                    return Person::search($validatedData['q'])->query(function (Builder $query): Builder {
                        return $query->with([
                            'media',
                        ])->withCount([
                            'movies',
                        ]);
                    })
                        ->paginate(25)
                        ->appends(request()->query());
                } elseif ($validatedData['type'] === 'series') {
                    return Series::search($validatedData['q'])->query(function (Builder $query): Builder {
                        return $query->withCount([
                            'movies',
                        ]);
                    })
                        ->paginate(25)
                        ->appends(request()->query());
                } elseif ($validatedData['type'] === 'studios') {
                    return Studio::search($validatedData['q'])->query(function (Builder $query): Builder {
                        return $query->withCount([
                            'movies',
                        ]);
                    })
                        ->paginate(25)
                        ->appends(request()->query());
                }

                // We default to movies
                return Movie::search($validatedData['q'])->query(function (Builder $query): Builder {
                    return $query->with([
                        'media',
                        'type',
                        'loveReactant.reactionTotal',
                        'loveReactant.reactions.type',
                    ]);
                })
                    ->paginate(25)
                    ->appends(request()->query());
            },
            'counts' => [
                'people' => $personCount,
                'movies' => $movieCount,
                'series' => $seriesCount,
                'studios' => $studioCount,
            ],
        ]);
    }
}
