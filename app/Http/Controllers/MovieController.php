<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App;
use App\Filters\FiltersFeaturedModelAge;
use App\Filters\FiltersMovieMedia;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Models\Movie;
use App\Models\MovieType;
use App\Models\Person;
use App\Models\Series;
use App\Models\Studio;
use Carbon\Carbon;
use Crawler;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class MovieController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Movie::class, 'movie');
        $this->middleware('auth')->except(['show', 'index']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('MovieIndex', [
            'movies' => function (): LengthAwarePaginator {
                return QueryBuilder::for(Movie::class)
                    ->defaultSort('-release_date')
                    ->allowedSorts([
                        'created_at',
                        'release_date',
                        'updated_at',
                        'title',
                        'popularity',
                    ])
                    ->allowedFilters([
                        AllowedFilter::custom('age', new FiltersFeaturedModelAge),
                        AllowedFilter::custom('media', new FiltersMovieMedia),
                        AllowedFilter::scope('recent', 'recentlyReleased'),
                        AllowedFilter::scope('upcoming', 'upcoming'),
                        AllowedFilter::scope('type', 'ofType'),
                        AllowedFilter::exact('studio_id'),
                    ])
                    ->with([
                        'media',
                        'models',
                        'type',
                        'loveReactant.reactions.reacter.reacterable',
                        'loveReactant.reactions.type',
                        'loveReactant.reactionCounters',
                        'loveReactant.reactionTotal',
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
            'movieTypes' => function (): Collection {
                return MovieType::orderBy('name')
                    ->get();
            },
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        // Get the studio search parameter
        $studioSearch = request()->query('studio', null);

        // Get the series search parameter
        $seriesSearch = request()->query('series', null);

        return Inertia::render('Item/Create', [
            'type' => 'movie',
            'series' => function () use ($seriesSearch): Collection {
                // If series search is set and is a string, return the results
                if ($seriesSearch && is_string($seriesSearch)) {
                    return Series::search($seriesSearch)->get();
                }

                return collect();
            },
            'studios' => function () use ($studioSearch): Collection {
                // If studio search is set and is a string, return the results
                if ($studioSearch && is_string($studioSearch)) {
                    return Studio::search($studioSearch)->get();
                }

                return collect();
            },
            'movieTypes' => MovieType::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovieRequest $request): RedirectResponse
    {
        /** @var array{title: string|null, originalTitle: string, releaseDate: Carbon|null, runtime: int|null, studioId: int|null, movieTypeId: int, seriesId: int|null} */
        $validatedData = $request->validated();

        $locale = App::getLocale();

        $movie = Movie::create([
            'title' => [
                $locale => $validatedData['title'] ?? null,
                'ja-JP' => $validatedData['originalTitle'],
            ],
            'length' => $validatedData['runtime'] ?? null,
            'studio_id' => $validatedData['studioId'] ?? null,
            'type_id' => $validatedData['movieTypeId'],
            'series_id' => $validatedData['seriesId'] ?? null,
        ]);

        return Redirect::route('movies.show', $movie->slug);
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie): Response
    {
        $movie->load([
            'studio',
            'series',
            'type',
            'media',
            'models',
            'versions',
            'models.media',
            'loveReactant.reactions.reacter.reacterable',
            'loveReactant.reactions.type',
            'loveReactant.reactionCounters',
            'loveReactant.reactionTotal',
        ]);

        // Don't log visits from bots, as it may skew the popularity score
        if (! Crawler::isCrawler()) {
            $movie->visit();
        }

        return Inertia::render('Item/Show', [
            'item' => $movie,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie): Response
    {
        $movie->load(['studio', 'media', 'models', 'versions']);

        $studioSearch = request()->query('studio', null);

        $seriesSearch = request()->query('series', null);

        $modelSearch = request()->query('model', null);

        return Inertia::render('Item/Edit', [
            'item' => $movie,
            'movieTypes' => fn () => MovieType::all(),
            'series' => function () use ($movie, $seriesSearch): Collection {
                if ($seriesSearch && is_string($seriesSearch)) {
                    return Series::search($seriesSearch)->get();
                }

                if ($movie->series) {
                    return collect([$movie->series]);
                }

                return collect();
            },
            'studios' => function () use ($movie, $studioSearch): Collection {
                if ($studioSearch && is_string($studioSearch)) {
                    return Studio::search($studioSearch)->get();
                }

                if ($movie->studio) {
                    return collect([$movie->studio]);
                }

                return collect();
            },
            'models' => function () use ($modelSearch): Collection {
                if ($modelSearch && is_string($modelSearch)) {
                    return Person::search($modelSearch)->get();
                }

                return collect();
            },
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMovieRequest $request, Movie $movie): RedirectResponse
    {
        /** @var array{title: string|null, original_title: string, release_date: Carbon|null, runtime: int|null, type_id: int, studio_id: int|null, series_id: int|null, is_vr: boolean, is_3d: boolean} */
        $validatedData = $request->validated();

        // TODO: Allow updating other locales. Maybe through another controller?
        $locale = App::getLocale();

        $movie->update([
            'title' => [
                $locale => $validatedData['title'] ?? null,
                'ja-JP' => $validatedData['original_title'],
            ],
            'release_date' => $validatedData['release_date'] ?? null,
            'runtime' => $validatedData['runtime'] ?? null,
            'type_id' => $validatedData['type_id'],
            'studio_id' => $validatedData['studio_id'] ?? null,
            'series_id' => $validatedData['series_id'] ?? null,
            'is_vr' => $validatedData['is_vr'],
            'is_3d' => $validatedData['is_3d'],
        ]);

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
