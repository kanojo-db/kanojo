<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\MediaCollectionType;
use App\Http\Requests\StoreMovieMediaRequest;
use App\Models\KanojoMedia;
use App\Models\Movie;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;
use Inertia\Response;

class MovieMediaController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(KanojoMedia::class, 'media');
        $this->middleware('auth')->except(['index']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Movie $movie): Response
    {
        $movie->load([
            'media',
            'media.loveReactant.reactions.reacter.reacterable',
            'media.loveReactant.reactions.type',
            'media.loveReactant.reactionCounters',
            'media.loveReactant.reactionTotal',
        ]);

        /** @var string $collectionName */
        $collectionName = request()->query('type', MediaCollectionType::FrontCover->value);

        $images = $movie->getMedia($collectionName);

        $mediaCollectionCount = $movie->media
            ->groupBy('collection_name')
            ->map(fn ($collection) => $collection->count());

        return Inertia::render('Item/Media', [
            'item' => $movie,
            'images' => $images,
            'mediaCollectionCount' => $mediaCollectionCount,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovieMediaRequest $request, Movie $movie): RedirectResponse
    {
        /** @var array{collection_name: string, media: \Illuminate\Http\UploadedFile} */
        $validatedData = $request->validated();

        if ($request->hasFile('media') && $request->file('media') instanceof UploadedFile && $request->file('media')->isValid()) {
            match ($validatedData['collection_name']) {
                MediaCollectionType::FrontCover->value => $movie->addPoster($validatedData['media']),
                MediaCollectionType::FullCover->value => $movie->addFanart($validatedData['media']),
                default => back()->withErrors(['media' => 'This type of media is not supported.']),
            };
        }

        return back();
    }

    /**
     * Deletes a media item from the movie.
     */
    public function destroy(Movie $movie, KanojoMedia $media): RedirectResponse
    {
        $movie->deleteMedia($media->id);

        return back();
    }
}
