<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieMediaRequest;
use App\Models\Person;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PersonMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Person $model): Response
    {
        $this->authorize('viewAny', $model);

        $model->load(['media']);

        $posters = $model->getMedia('profile');

        return Inertia::render('Person/Media', ['person' => $model, 'posters' => $posters]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovieMediaRequest $request, Person $model): RedirectResponse
    {
        $this->authorize('create', $model);

        /** @var array{collection_name: string, media: \Illuminate\Http\UploadedFile} */
        $validatedData = $request->validated();

        $model->addMediaFromRequest('media')
            ->toMediaCollection($validatedData['collection_name']);

        return back();
    }
}
