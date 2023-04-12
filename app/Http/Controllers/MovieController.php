<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App;
use App\Filters\FiltersFeaturedModelAge;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Models\Movie;
use App\Models\MovieType;
use App\Models\Studio;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
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
                        'loveReactant.reactions.reacter.reacterable',
                        'loveReactant.reactions.type',
                        'loveReactant.reactionCounters',
                        'loveReactant.reactionTotal',
                    ])
                    ->defaultSort('-created_at')
                    ->allowedSorts(['created_at', 'product_code'])
                    ->allowedFilters([
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

        return Inertia::render('Movie/Create', ['studios' => $studios, 'movieTypes' => $movie_types, 'tags' => $tags]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovieRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        $locale = App::getLocale();

        $movie = Movie::create([
            'title' => [
                $locale => $validatedData->title,
                'ja-JP' => $validatedData->original_title,
            ],
            'product_code' => $validatedData->product_code,
            'release_date' => $validatedData->release_date,
            'length' => $validatedData->length,
            'studio_id' => $validatedData->studio_id,
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
            'loveReactant.reactions.reacter.reacterable',
            'loveReactant.reactions.type',
            'loveReactant.reactionCounters',
            'loveReactant.reactionTotal',
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
     */
    public function edit(Movie $movie): Response
    {
        $movie->load(['studio', 'media', 'tags']);

        return Inertia::render('Movie/Edit', ['movie' => $movie]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMovieRequest $request, Movie $movie): RedirectResponse
    {
        $validatedData = $request->validated();

        // TODO: Allow updating other locales. Maybe through another controller?
        $locale = App::getLocale();

        $movie->update([
            'title' => [
                $locale => $validatedData->title,
                'ja-JP' => $validatedData->original_title,
            ],
            'product_code' => $validatedData->product_code,
            'release_date' => $validatedData->release_date,
            'runtime' => $validatedData->runtime ?? 0,
        ]);

        // If release_date was changed and we have models linked, update the ages
        if ($movie->wasChanged('release_date') && $movie->models()->count() > 0) {
            foreach ($movie->models as $model) {
                if ($model->birthday === null) {
                    continue;
                }

                $age = Carbon::parse($movie->release_date)->diffInYears(Carbon::parse($model->birthdate));

                $movie->models()->updateExistingPivot($model->id, ['age' => $age]);
            }
        }

        return Redirect::route('movies.show', $movie);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie): RedirectResponse
    {
        $movie->delete();

        return Redirect::route('movies.index');
    }
}
