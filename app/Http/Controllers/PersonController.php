<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        $models = QueryBuilder::for(Person::class)
        ->defaultSort('-created_at')
        ->allowedSorts('created_at', 'birthdate', 'height')
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

        $birth_counts = Person::select(
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

        $height_counts = Person::select(
            DB::raw('height as value'),
            DB::raw('COUNT(*) AS count')
        )
        ->where('height', '!=', null)
        ->groupBy('value')
        ->orderBy('value')
        ->get();

        $bust_counts = Person::select(
            DB::raw('bust as value'),
            DB::raw('COUNT(*) AS count')
        )
        ->where('bust', '!=', null)
        ->groupBy('value')
        ->orderBy('value')
        ->get();

        $waist_counts = Person::select(
            DB::raw('waist as value'),
            DB::raw('COUNT(*) AS count')
        )
        ->where('waist', '!=', null)
        ->groupBy('value')
        ->orderBy('value')
        ->get();

        $hip_counts = Person::select(
            DB::raw('hip as value'),
            DB::raw('COUNT(*) AS count')
        )
        ->where('hip', '!=', null)
        ->groupBy('value')
        ->orderBy('value')
        ->get();

        return Inertia::render('Person/Index', [
            'models' => $models,
            'birth_counts' => $birth_counts,
            'height_counts' => $height_counts,
            'bust_counts' => $bust_counts,
            'waist_counts' => $waist_counts,
            'hip_counts' => $hip_counts,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Person  $person
     */
    public function show($person): \Inertia\Response
    {
        $personRecord = Person::with(['media', 'movies' => function($query) {
            $query->orderBy('release_date', 'desc');
        }, 'movies.media'])->find($person);

        if (!$personRecord) {
            abort(404);
        }

        $personRecord->visit();

        return Inertia::render('Person/Show', ['person' => $personRecord]);
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
                'en' => $data['name'],
                'jp' => $data['original_name'],
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
