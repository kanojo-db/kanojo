<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App;
use App\Models\Movie;
use App\Models\Person;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
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
            'birthCounts' => function (): array {
                return Person::select(
                    DB::raw('YEAR(birthdate) AS value'),
                    DB::raw('COUNT(*) AS count')
                )
                ->where('birthdate', '!=', null)
                ->groupBy(
                    DB::raw('value')
                )
                ->orderBy(
                    DB::raw('value')
                )
                ->get();
            },
            'heightCounts' => function (): array {
                return Person::select(
                    DB::raw('height as value'),
                    DB::raw('COUNT(*) AS count')
                )
                ->where('height', '!=', null)
                ->groupBy('value')
                ->orderBy('value')
                ->get();
            },
            'bustCounts' => function (): array {
                return Person::select(
                    DB::raw('bust as value'),
                    DB::raw('COUNT(*) AS count')
                )
                ->where('bust', '!=', null)
                ->groupBy('value')
                ->orderBy('value')
                ->get();
            },
            'waistCounts' => function (): array {
                return Person::select(
                    DB::raw('waist as value'),
                    DB::raw('COUNT(*) AS count')
                )
                ->where('waist', '!=', null)
                ->groupBy('value')
                ->orderBy('value')
                ->get();
            },
            'hipCounts' => function (): array {
                return  Person::select(
                    DB::raw('hip as value'),
                    DB::raw('COUNT(*) AS count')
                )
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

                $model->visit();

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
            }
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
    public function update(Request $request, Person $model): RedirectResponse
    {
        $locale = App::getLocale();

        $request->validate([
            'name' => ['nullable', 'string'],
            'original_name' => ['required'],
            'birthdate' => ['nullable', 'date'],
            'country' => ['nullable', 'string'],
            'career_start' => ['nullable', 'date'],
            'career_end' => ['nullable', 'date'],
            'blood_type' => ['nullable', Rule::in(['AB', 'A', 'B', 'O'])],
            'cup_size' => ['nullable', Rule::in(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'])],
            'height' => ['nullable', 'integer', 'max:250'],
            'bust' => ['nullable', 'integer', 'max:250'],
            'waist' => ['nullable', 'integer', 'max:250'],
            'hip' => ['nullable', 'integer', 'max:250'],
            'dob_doubt' => ['boolean'],
        ]);

        $validatedData = $request->validated();

        $model->update([
            'name' => [
                $locale => $validatedData->name,
                'ja-JP' => $validatedData->original_name,
            ],
            'birthdate' => $validatedData->birthdate,
            'country' => $validatedData->country,
            'career_start' => $validatedData->career_start,
            'career_end' => $validatedData->career_end,
            'blood_type' => $validatedData->blood_type,
            'cup_size' => $validatedData->cup_size,
            'height' => $validatedData->height,
            'bust' => $validatedData->bust,
            'waist' => $validatedData->waist,
            'hip' => $validatedData->hip,
            'dob_doubt' => $validatedData->dob_doubt,
        ]);

        return Redirect::route('models.show', $model);
    }
}
