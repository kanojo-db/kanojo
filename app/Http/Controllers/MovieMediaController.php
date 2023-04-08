<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\MediaCollectionType;
use App\Http\Requests\StoreMovieMediaRequest;
use App\Models\Movie;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class MovieMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Movie $movie): Response
    {
        $movie->load('media');

        $collectionName = request()->query('type', MediaCollectionType::FrontCover->value);

        $images = $movie->getMedia($collectionName);

        // Get the count of every media collection
        $mediaCollectionCount = $movie->media->groupBy('collection_name')->map(fn ($collection) => $collection->count());

        return Inertia::render('Movie/Media', ['movie' => $movie, 'images' => $images, 'mediaCollectionCount' => $mediaCollectionCount]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovieMediaRequest $request, Movie $movie): RedirectResponse
    {
        $validatedData = $request->validated();

        $movie->addMediaFromRequest('media')
            ->toMediaCollection($validatedData['collection_name']);

        return back();
    }
}
