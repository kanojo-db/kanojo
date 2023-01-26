<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\Movie;
use App;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
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
            'birthCounts' => function () {
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
            'heightCounts' => function () {
                return Person::select(
                    DB::raw('height as value'),
                    DB::raw('COUNT(*) AS count')
                )
                ->where('height', '!=', null)
                ->groupBy('value')
                ->orderBy('value')
                ->get();
            },
            'bustCounts' => function () {
                return Person::select(
                    DB::raw('bust as value'),
                    DB::raw('COUNT(*) AS count')
                )
                ->where('bust', '!=', null)
                ->groupBy('value')
                ->orderBy('value')
                ->get();
            },
            'waistCounts' => function () {
                return Person::select(
                    DB::raw('waist as value'),
                    DB::raw('COUNT(*) AS count')
                )
                ->where('waist', '!=', null)
                ->groupBy('value')
                ->orderBy('value')
                ->get();
            },
            'hipCounts' => function () {
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
     * @param \App\Models\Person  $person
     */
    public function show(Person $model): \Inertia\Response
    {
        $model->load([
            'media',
        ]);

        $model->visit();

        // Get all movies with this person, and count them, without going through the relation
        $movieCount = Movie::whereHas('models', function($query) use ($model) {
            $query->where('person_id', $model->id);
        })->withoutGlobalScope('filterHidden')->count();

        $movies = Movie::whereHas('models', function($query) use ($model) {
            $query->where('person_id', $model->id);
        })->with([
            'media',
            'type',
            'loveReactant.reactions.reacter.reacterable',
            'loveReactant.reactions.type',
            'loveReactant.reactionCounters',
            'loveReactant.reactionTotal'
        ])->orderBy('release_date', 'desc')->paginate(25);

        return Inertia::render('Person/Show', ['person' => $model, 'movieCount' => $movieCount, 'movies' => $movies]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Person  $person
     */
    public function edit($id): \Inertia\Response
    {
        $person = Person::find($id);

        $person->load('media');

        return Inertia::render('Person/Edit', ['person' => $person]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Models\Movie  $movie
     */
    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $locale = App::getLocale();

        $data = $request->validate([
            'name' => [],
            'original_name' => ['required'],
            'birthdate' => [],
            'country' => [],
            'career_start' => [],
            'career_end' => [],
            'blood_type' => [],
            'cup_size' => [],
            'height' => [],
            'bust' => [],
            'waist' => [],
            'hip' => [],
            'dob_doubt' => ['boolean'],
        ]);

        $person = Person::find($id);

        $person->update([
            'name' => [
                $locale => $data['name'],
                'ja-JP' => $data['original_name'],
            ],
            'birthdate' => $data['birthdate'],
            'country' => $data['country'],
            'career_start' => $data['career_start'],
            'career_end' => $data['career_end'],
            'blood_type' => $data['blood_type'],
            'cup_size' => $data['cup_size'],
            'height' => $data['height'],
            'bust' => $data['bust'],
            'waist' => $data['waist'],
            'hip' => $data['hip'],
            'dob_doubt' => $data['dob_doubt'],
        ]);

        return Redirect::route('models.show', $person);
    }
}
