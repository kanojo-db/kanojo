<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\MediaCollectionType;
use App\Http\Requests\StoreMovieMediaRequest;
use App\Models\Movie;
use Inertia\Inertia;
use Ramsey\Uuid\Uuid;

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
        // TODO: Use automatic model binding here
        $movie = Movie::find($id);

        if ($request->hasFile('media') && $request->file('media')->isValid()) {
            // TODO: Use MediaCollectionType enum instead of passing the request's parameter directly.
            $movie->addMediaFromRequest('media')->usingFileName(Uuid::uuid4()->toString())->toMediaCollection($request->collection_name);
        }
    }
}
