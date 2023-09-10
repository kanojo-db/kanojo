<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MoviePreviewInternalController extends Controller
{
    /**
     * Internal route to render the preview.
     */
    public function show(Request $request, Movie $movie)
    {
        $movie->load(['media', 'studio', 'series', 'models' => function ($query) {
            $query->withPivot('age');
        }]);

        return Inertia::render('Preview/Movie', [
            'movie' => $movie,
        ]);
    }
}
