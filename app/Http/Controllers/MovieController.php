<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Studio;
use App\Models\User;
use App\Http\Requests\StoreMovieRequest;
use App\Models\MovieType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Tags\Tag;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        $movies = Movie::with('media')->latest()->paginate(25);

        return Inertia::render('Movie/Index', ['movies' => $movies]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Inertia\Response
    {
        $studios = Studio::all();
        $movie_types = MovieType::all();
        $tags = Tag::getWithType('movie');

        return Inertia::render('Movie/Create', ['studios' => $studios, 'movie_types' => $movie_types, 'tags' => $tags]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovieRequest $request): \Illuminate\Http\RedirectResponse
    {
        $studio_id = $request->studio_id;
        $studio = Studio::find($studio_id);

        $movie_type_id = $request->movie_type_id;
        $movie_type = MovieType::find($movie_type_id);

        $movie = $studio->movies()->create([
            'title' => [
                'en' => $request->title,
                'jp' => $request->original_title,
            ],
            'product_code' => $request->product_code,
            'release_date' => $request->release_date,
            'length' => $request->length,
        ]);

        $movie->type()->associate($movie_type);
        $movie->save();

        if ($request->hasFile('poster') && $request->file('poster')->isValid()) {
            $movie->addMediaFromRequest('poster')->toMediaCollection('poster');
        }

        $movie->attachTags($request->tags);

        return Redirect::route('movies.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($movie): \Inertia\Response
    {
        $movieRecord = Movie::with([
            'studio',
            'type',
            'tags',
            'media',
            'models',
            'models.media',
            'loveReactant.reactions.reacter.reacterable',
            'loveReactant.reactions.type',
            'loveReactant.reactionCounters',
            'loveReactant.reactionTotal'
        ])->where('slug', $movie)->firstOrFail();

        // If it's in the user's favorites, mark it as such
        if (Auth::check()) {
            $user = Auth::user();

            $movieRecord->is_favorite = $user->favorites->contains($movieRecord);
        }

        // If it's in the user's wishlist, mark it as such
        if (Auth::check()) {
            $user = Auth::user();

            $movieRecord->is_wishlist = $user->wishlist->contains($movieRecord);
        }

        // If it's in the user's collection list, mark it as such
        if (Auth::check()) {
            $user = Auth::user();

            $movieRecord->is_collection = $user->collection->contains($movieRecord);
        }

        $movieRecord->visit();

        return Inertia::render('Movie/Show', ['movie' => $movieRecord]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Movie  $movie
     */
    public function edit(\App\Models\Movie $movie): \Inertia\Response
    {
        $movie->load('studio', 'media', 'tags', 'type');

        return Inertia::render('Movie/Edit', ['movie' => $movie]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \App\Models\Movie  $movie
     */
    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'title' => [],
            'original_title' => ['required'],
            'product_code' => ['required'],
            'release_date' => [],
            'runtime' => [],
        ]);

        $movie = Movie::find($id);

        $movie->update([
            'title' => [
                'en' => $data['title'],
                'jp' => $data['original_title'],
            ],
            'product_code' => $data['product_code'],
            'release_date' => $data['release_date'],
            'runtime' => $data['runtime'] ?? 0,
        ]);

        return Redirect::route('movies.show', $movie);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Movie  $movie
     */
    public function destroy(\App\Models\Movie $movie): \Illuminate\Http\RedirectResponse
    {
        $movie->delete();

        return Redirect::route('movies.index');
    }
}
