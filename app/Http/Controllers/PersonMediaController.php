<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieMediaRequest;
use App\Models\Person;
use App\Models\Studio;
use App\Models\User;
use App\Http\Requests\StoreMovieRequest;
use App\Models\MovieType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Spatie\Tags\Tag;

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
            $person->addMediaFromRequest('media')->toMediaCollection($request->collection_name);
        }

        return;
    }
}
