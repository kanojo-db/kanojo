<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App;
use App\Http\Requests\UpdatePersonRequest;
use App\Models\Movie;
use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Person/Index', [
            'models' => function () {
                return QueryBuilder::for(Person::class)
                    ->defaultSort('-created_at')
                    ->allowedSorts(['created_at', 'birthdate', 'height'])
                    ->allowedFilters([
                        AllowedFilter::scope('born', 'born_between'),
                        'height',
                        'bust',
                        'waist',
                        'hip',
                    ])
                    ->with('media')
                    ->paginate(25)
                    ->appends(request()->query());
            },
            'birthCounts' => function (): Collection {
                return Person::select([
                    DB::raw('YEAR(birthdate) AS value'),
                    DB::raw('COUNT(*) AS count')
                ])
                ->where('birthdate', '!=', null)
                ->groupBy(
                    DB::raw('value')
                )
                ->orderBy(
                    DB::raw('value')
                )
                ->get();
            },
            'heightCounts' => function (): Collection {
                return Person::select([
                    DB::raw('height as value'),
                    DB::raw('COUNT(*) AS count')
                ])
                ->where('height', '!=', null)
                ->groupBy('value')
                ->orderBy('value')
                ->get();
            },
            'bustCounts' => function (): Collection {
                return Person::select([
                    DB::raw('bust as value'),
                    DB::raw('COUNT(*) AS count')
                ])
                ->where('bust', '!=', null)
                ->groupBy('value')
                ->orderBy('value')
                ->get();
            },
            'waistCounts' => function (): Collection {
                return Person::select([
                    DB::raw('waist as value'),
                    DB::raw('COUNT(*) AS count')
                ])
                ->where('waist', '!=', null)
                ->groupBy('value')
                ->orderBy('value')
                ->get();
            },
            'hipCounts' => function (): Collection {
                return Person::select([
                    DB::raw('hip as value'),
                    DB::raw('COUNT(*) AS count')
                ])
                    ->where('hip', '!=', null)
                    ->groupBy('value')
                    ->orderBy('value')
                    ->get();
            },
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Person  $person
     */
    public function show(Person $model): Response
    {
        return Inertia::render('Person/Show', [
            'person' => function () use ($model): Person {
                $model->load([
                    'media',
                ]);

                return $model;
            },
            'movieCount' => function () use ($model): int {
                return Movie::whereHas('models', function (Builder $query) use ($model) {
                    $query->where('person_id', $model->id);
                })->count();
            },
            'movies' => function () use ($model): LengthAwarePaginator {
                return Movie::whereHas('models', function (Builder $query) use ($model) {
                    $query->where('person_id', $model->id);
                })->with([
                    'media',
                    'type',
                    'loveReactant.reactions.reacter.reacterable',
                    'loveReactant.reactions.type',
                    'loveReactant.reactionCounters',
                    'loveReactant.reactionTotal',
                ])->orderBy('release_date', 'desc')->paginate(25);
            },
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Person $model): Response
    {
        $model->load('media');

        return Inertia::render('Person/Edit', ['person' => $model]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonRequest $request, Person $model): RedirectResponse
    {
        $validatedData = $request->validated();

        $locale = App::getLocale();

        $model->update([
            'name' => [
                $locale => $validatedData['name'],
                'ja-JP' => $validatedData['original_name'],
            ],
            'birthdate' => $validatedData['birthdate'],
            'country' => $validatedData['country'],
            'career_start' => $validatedData['career_start'],
            'career_end' => $validatedData['career_end'],
            'blood_type' => $validatedData['blood_type'],
            'cup_size' => $validatedData['cup_size'],
            'height' => $validatedData['height'],
            'bust' => $validatedData['bust'],
            'waist' => $validatedData['waist'],
            'hip' => $validatedData['hip'],
        ]);

        // If birthdate was changed, update all movies with this person
        if ($model->wasChanged('birthdate')) {
            $model->load('movies');

            foreach ($model->movies as $movie) {
                // If the release date is not set, skip
                if (! $movie->release_date) {
                    continue;
                }

                // If the release date is before the birthdate, skip
                if (Carbon::parse($movie->release_date)->isBefore(Carbon::parse($model->birthdate))) {
                    continue;
                }

                $age = Carbon::parse($movie->release_date)->diffInYears(Carbon::parse($model->birthdate));
                $model->movies()->updateExistingPivot($movie->id, ['age' => $age]);
            }
        }

        return Redirect::route('models.show', $model);
    }
}
