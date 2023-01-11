<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Person;
use App\Models\Studio;
use Inertia\Inertia;

class StudioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): void
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): void
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): void
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Studio  $studio
     * @return \Inertia\Response
     */
    public function show(Studio $studio)
    {
        $movies = Movie::orderBy('release_date', 'desc')
            ->where('studio_id', $studio->id)
            ->with([
                'media',
                'loveReactant.reactions.reacter.reacterable',
                'loveReactant.reactions.type',
                'loveReactant.reactionCounters',
                'loveReactant.reactionTotal',
                'type',
            ])
            ->paginate(25);

        // Get all movies from this studio, and count them, without going through the relation
        $movieCount = Movie::where('studio_id', $studio->id)->withoutGlobalScope('filterHidden')->count();

        // Get all people who have appeared in movies by this studio
        $models = Person::whereHas('movies', function ($query) use ($studio) {
            $query->where('studio_id', $studio->id)->withoutGlobalScope('filterHidden');
        })->withCount([
            'movies' => function ($query) use ($studio) {
                $query->where('studio_id', $studio->id)->withoutGlobalScope('filterHidden');
            },
        ])->orderBy('movies_count', 'desc')->with(['media'])->take(10)->get();

        return Inertia::render('Studio/Show', [
            'studio' => $studio,
            'movies' => $movies,
            'models' => $models,
            'movieCount' => $movieCount,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Studio  $studio
     */
    public function edit(Studio $studio): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Studio  $studio
     */
    public function update(Studio $studio): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Studio  $studio
     */
    public function destroy(Studio $studio): void
    {
        //
    }
}
