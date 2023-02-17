<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Person;
use App\Models\Studio;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Inertia\Inertia;
use Inertia\Response;

class StudioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): void
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): void
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): void
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(Studio $studio): Response
    {
        return Inertia::render('Studio/Show', [
            'studio' => $studio,
            'movies' => function () use ($studio): Collection {
                return Movie::orderBy('release_date', 'desc')
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
            },
            'models' => function () use ($studio) {
                return Person::whereHas('movies', function (Builder $query) use ($studio) {
                    $query->where('studio_id', $studio->id);
                })->withCount([
                    'movies' => function (Builder $query) use ($studio) {
                        $query->where('studio_id', $studio->id);
                    },
                ])->orderBy('movies_count', 'desc')->with(['media'])->take(10)->get();
            },
            'movieCount' => Movie::where('studio_id', $studio->id)->count(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Studio $studio): void
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Studio $studio): void
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Studio $studio): void
    {

    }
}
