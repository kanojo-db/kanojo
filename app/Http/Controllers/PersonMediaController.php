<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\MediaCollectionType;
use App\Http\Requests\StoreMovieMediaRequest;
use App\Models\KanojoMedia;
use App\Models\Person;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PersonMediaController extends Controller
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
    public function index(Person $model): Response
    {
        $model->load([
            'media',
            'media.loveReactant.reactions.reacter.reacterable',
            'media.loveReactant.reactions.type',
            'media.loveReactant.reactionCounters',
            'media.loveReactant.reactionTotal',
        ]);

        /** @var string $collectionName */
        $collectionName = request()->query('type', MediaCollectionType::Profile->value);

        $images = $model->getMedia($collectionName);

        $mediaCollectionCount = $model->media
            ->groupBy('collection_name')
            ->map(fn ($collection) => $collection->count());

        return Inertia::render('Item/Media', [
            'item' => $model,
            'images' => $images,
            'mediaCollectionCount' => $mediaCollectionCount,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovieMediaRequest $request, Person $model): RedirectResponse
    {
        /** @var array{collection_name: string, media: \Illuminate\Http\UploadedFile} */
        $validatedData = $request->validated();

        $model->addPoster($validatedData['media']);

        return back();
    }

    /**
     * Deletes a media item from the movie.
     */
    public function destroy(Person $model, KanojoMedia $media): RedirectResponse
    {
        $model->deleteMedia($media->id);

        return back();
    }
}
