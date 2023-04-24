<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\MovieExternalIdsUpdateRequest;
use App\Models\Movie;
use Illuminate\Http\RedirectResponse;

class MovieExternalIdsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(MovieExternalIdsUpdateRequest $request, Movie $movie): RedirectResponse
    {
        $validatedData = $request->validated();

        $movie->update($validatedData);

        return redirect()->route('movies.show', $movie);
    }
}
