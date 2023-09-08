<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\MovieVersionRequest;
use App\Models\Movie;
use App\Models\MovieVersion;
use Illuminate\Http\RedirectResponse;

class MovieVersionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(MovieVersionRequest $request, Movie $movie): RedirectResponse
    {
        $validatedData = $request->validated();

        $movie->versions()->create($validatedData);

        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MovieVersionRequest $request, Movie $movie, MovieVersion $version): RedirectResponse
    {
        $validatedData = $request->validated();

        $version->update($validatedData);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie, MovieVersion $version): RedirectResponse
    {
        $version->delete();

        return back()->with('success', 'Movie version deleted.');
    }
}
