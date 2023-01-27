<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieMediaRequest;
use App\Models\Movie;
use Inertia\Inertia;

class MovieMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Movie $movie): \Inertia\Response
    {
        $movie->load('media');

        $posters = $movie->getMedia('poster');

        return Inertia::render('Movie/Media', ['movie' => $movie, 'posters' => $posters]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovieMediaRequest $request, $id): void
    {
        $movie = Movie::find($id);

        if ($request->hasFile('media') && $request->file('media')->isValid()) {
            $movie->addMediaFromRequest('media')->toMediaCollection($request->collection_name);
        }
    }
}
