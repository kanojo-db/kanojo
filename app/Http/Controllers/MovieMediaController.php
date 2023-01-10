<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieMediaRequest;
use App\Models\Movie;
use App\Models\User;
use App\Http\Requests\StoreMovieRequest;
use App\Models\MovieType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Spatie\Tags\Tag;

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
        $movie = Movie::find($id);

        if ($request->hasFile('media') && $request->file('media')->isValid()) {
            $movie->addMediaFromRequest('media')->toMediaCollection($request->collection_name);
        }
    }
}
