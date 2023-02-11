<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieMediaRequest;
use App\Models\Person;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\File;
use Inertia\Inertia;
use Inertia\Response;
use Ramsey\Uuid\Uuid;

class PersonMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Person $model): Response
    {
        $model->load(['media']);

        $posters = $model->getMedia('profile');

        return Inertia::render('Person/Media', ['person' => $model, 'posters' => $posters]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovieMediaRequest $request, Person $model): RedirectResponse
    {
        $validatedData = $request->validated();

        $model->addMediaFromRequest('media')
            ->usingFileName(Uuid::uuid4()->toString())
            ->toMediaCollection($validatedData->collection_name);

        return back();
    }
}
