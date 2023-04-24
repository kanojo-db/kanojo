<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\MediaCollectionType;
use App\Http\Requests\StoreMovieMediaRequest;
use App\Models\KanojoMedia;
use App\Models\Studio;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class StudioMediaController extends Controller
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
    public function index(Studio $studio): Response
    {
        $studio->load([
            'media',
            'media.loveReactant.reactions.reacter.reacterable',
            'media.loveReactant.reactions.type',
            'media.loveReactant.reactionCounters',
            'media.loveReactant.reactionTotal',
        ]);

        /** @var string $collectionName */
        $collectionName = request()->query('type', MediaCollectionType::Logo->value);

        $images = $studio->getMedia($collectionName);

        $mediaCollectionCount = $studio->media
            ->groupBy('collection_name')
            ->map(fn ($collection) => $collection->count());

        return Inertia::render('Item/Media', [
            'item' => $studio,
            'images' => $images,
            'mediaCollectionCount' => $mediaCollectionCount,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovieMediaRequest $request, Studio $studio): RedirectResponse
    {
        /** @var array{collection_name: string, media: \Illuminate\Http\UploadedFile} */
        $validatedData = $request->validated();

        $studio->addLogo($validatedData['media']);

        return back();
    }

    /**
     * Deletes a media item from the movie.
     */
    public function destroy(Studio $studio, KanojoMedia $media): RedirectResponse
    {
        $studio->deleteMedia($media->id);

        return back();
    }
}
