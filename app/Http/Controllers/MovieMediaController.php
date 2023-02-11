<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieMediaRequest;
use App\Models\Movie;
use Inertia\Inertia;
use Inertia\Response;
use Ramsey\Uuid\Uuid;

class MovieMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Movie $movie): Response
    {
        $movie->load('media');

        $posters = $movie->getMedia('poster');

        return Inertia::render('Movie/Media', ['movie' => $movie, 'posters' => $posters]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovieMediaRequest $request, Movie $movie): void
    {
        $validatedData = $request->validated();

        $movie->addMediaFromRequest('media')
            ->usingFileName(Uuid::uuid4()->toString())
            ->toMediaCollection($validatedData->collection_name);
    }
}
