<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieMediaRequest;
use App\Models\Person;
use Inertia\Inertia;

class PersonMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id): \Inertia\Response
    {
        $person = Person::with('media')->find($id);

        $posters = $person->getMedia('profile');

        return Inertia::render('Person/Media', ['person' => $person, 'posters' => $posters]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovieMediaRequest $request, $id): void
    {
        $person = Person::find($id);

        if ($request->hasFile('media') && $request->file('media')->isValid()) {
            // TODO: Use MediaCollectionType enum instead of passing the request's parameter directly.
            $person->addMediaFromRequest('media')->toMediaCollection($request->collection_name);
        }

    }
}
