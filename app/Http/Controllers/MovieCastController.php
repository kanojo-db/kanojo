<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Person;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovieCastController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Movie::class, 'movie');
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Movie $movie): RedirectResponse
    {
        $validatedData = $request->validate([
            'model_id' => 'required|exists:people,id',
        ]);

        $movie->models()->attach($validatedData['model_id']);

        return back()->with('success', 'Model added.');
    }

    /**
     * Update the specified resource in storage.
     * This is MAINLY used to lock/unlock a model.
     */
    public function update(Request $request, Movie $movie, Person $model): RedirectResponse
    {
        /** @var object{locked: bool, age: int|null}|null $pivot */
        $pivot = DB::table('movie_person')
            ->where('movie_id', $movie->id)
            ->where('person_id', $model->id)
            ->first();

        // Check if the pivot table record exists
        if ($pivot === null) {
            return back()->with('error', 'Model not found.');
        }

        // Check if the pivot table record is locked
        if ($pivot->locked) {
            $movie->models()->updateExistingPivot($model->id, ['locked' => false]);

            return back()->with('success', 'Model unlocked.');
        }

        $movie->models()->updateExistingPivot($model->id, ['locked' => true]);

        return back()->with('success', 'Model locked.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Movie $movie, Person $model): RedirectResponse
    {
        /** @var object{locked: bool, age: int|null}|null $pivot */
        $pivot = DB::table('movie_person')
            ->where('movie_id', $movie->id)
            ->where('person_id', $model->id)
            ->first();

        // Check if the pivot table record exists
        if (! $pivot) {
            return back()->with('error', 'Model not found.');
        }

        // Check if the pivot table record is locked
        if ($pivot->locked) {
            return back()->with('error', 'Cannot remove locked model.');
        }

        $movie->models()->detach($model->id);

        return back()->with('success', 'Model removed.');
    }
}
