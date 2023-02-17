<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App;
use App\Filters\FiltersFeaturedModelAge;
use App\Http\Requests\StoreMovieRequest;
use App\Models\Movie;
use App\Models\MovieType;
use App\Models\Studio;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\Tags\Tag;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Movie/Index', [
            'movies' => function (): LengthAwarePaginator {
                return QueryBuilder::for(Movie::class)
                    ->with([
                        'media',
                        'models',
                        'type',
                    ])
                    ->defaultSort('-created_at')
                    ->allowedSorts(['created_at', 'product_code'])
                    ->allowedFilters([
                        /**
                         * @psalm-suppress InvalidArgument -- FiltersFeaturedModelAge implements Filter
                         */
                        AllowedFilter::custom('age', new FiltersFeaturedModelAge),
                    ])
                    ->paginate(25)
                    ->appends(request()->query());
            },
            'ageCounts' => function (): Collection {
                return DB::table('movie_person')->select([
                    DB::raw('age AS value'),
                    DB::raw('COUNT(*) AS count'),
                ])
                ->where('age', '!=', null)
                ->groupBy('value')
                ->orderBy('value')
                ->get();
            },
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $studios = Studio::all();
        $movie_types = MovieType::all();
        $tags = Tag::getWithType('movie');

        return Inertia::render('Movie/Create', ['studios' => $studios, 'movie_types' => $movie_types, 'tags' => $tags]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovieRequest $request): RedirectResponse
    {
        $validTypes = MovieType::select('id')->all()->pluck('id');

        $request->validate([
            'studio_id' => ['nullable', 'integer'],
            'movie_type_id' => ['required', 'integer', Rule::in($validTypes)],
            'title' => ['nullable', 'string'],
            'original_title' => ['required', 'string'],
            'product_code' => ['required', 'string'],
            'release_date' => ['nullable', 'date'],
            'length' => ['nullable', 'integer'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string'],
        ]);
        
        $validatedData = $request->validated();

        $locale = App::getLocale();

        /** @var Studio|null */
        $studio = $validatedData->studio_id !== null ? Studio::firstOrFail($validatedData->studio_id) : null;

        $movie = Movie::create([
            'title' => [
                $locale => $validatedData->title,
                'ja-JP' => $validatedData->original_title,
            ],
            'product_code' => $validatedData->product_code,
            'release_date' => $validatedData->release_date,
            'length' => $validatedData->length,
            'studio_id' => $studio,
            'movie_type_id' => $validatedData->movie_type_id,
        ]);

        $movie->attachTags($validatedData->tags);

        $movie->save();

        return Redirect::route('movies.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $movie): Response
    {
        $movieRecord = Movie::with([
            'studio',
            'type',
            'tags',
            'media',
            'models',
            'models.media',
        ])->where('slug', $movie)->firstOrFail();

        /** @var User|null */
        $user = Auth::user();

        // If it's in the user's favorites, mark it as such
        if (Auth::check() && $user !== null) {
            $movieRecord->is_favorite = $user->favorites->contains($movieRecord);
        }

        // If it's in the user's wishlist, mark it as such
        if (Auth::check() && $user !== null) {
            $movieRecord->is_wishlist = $user->wishlist->contains($movieRecord);
        }

        // If it's in the user's collection list, mark it as such
        if (Auth::check() && $user !== null) {
            $movieRecord->is_collection = $user->collection->contains($movieRecord);
        }

        return Inertia::render('Movie/Show', ['movie' => $movieRecord]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Movie  $movie
     */
    public function edit(Movie $movie): Response
    {
        $movie->load(['studio', 'media', 'tags']);

        return Inertia::render('Movie/Edit', ['movie' => $movie]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        // TODO: Allow updating other locales. Maybe through another controller?
        $locale = App::getLocale();

        $request->validate([
            'title' => [],
            'original_title' => ['required'],
            'product_code' => ['required'],
            'release_date' => [],
            'runtime' => [],
        ]);
        
        $validatedData = $request->validated();

        $movie = Movie::findOrFail($id);

        $movie->update([
            'title' => [
                $locale => $validatedData->title,
                'ja-JP' => $validatedData->original_title,
            ],
            'product_code' => $validatedData->product_code,
            'release_date' => $validatedData->release_date,
            'runtime' => $validatedData->runtime ?? 0,
        ]);

        return Redirect::route('movies.show', $movie);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Movie  $movie
     */
    public function destroy(Movie $movie): RedirectResponse
    {
        $movie->delete();

        return Redirect::route('movies.index');
    }
}
