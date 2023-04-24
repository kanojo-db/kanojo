<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreSeriesRequest;
use App\Models\Series;
use App\Models\Studio;
use App\Sorts\RelationshipCountSort;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class SeriesController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Series::class, 'series');
        $this->middleware('auth')->except(['show', 'index']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('SeriesIndex', [
            'series' => function (): LengthAwarePaginator {
                return QueryBuilder::for(Series::class)
                    ->allowedSorts([
                        'created_at',
                        'name',
                        'updated_at',
                        AllowedSort::custom('movies', new RelationshipCountSort(), 'movies'),
                    ])
                    ->defaultSort('-created_at')
                    ->withCount([
                        'movies',
                    ])
                    ->paginate(25)
                    ->appends(request()->query());
            },
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $studioSearch = request()->query('studio', null);

        return Inertia::render('Item/Create', [
            'type' => 'series',
            'studios' => function () use ($studioSearch) {
                // If studio search is set and is a string, return the results
                if ($studioSearch && is_string($studioSearch)) {
                    return collect(Studio::search($studioSearch)->get());
                }

                return collect();
            },
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSeriesRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        $locale = App::getLocale();

        $series = Series::create([
            'title' => [
                $locale => $validatedData['title'],
                'ja-JP' => $validatedData['original_title'],
            ],
            'studio_id' => $validatedData['studioId'] ?? null,
        ]);

        return redirect()->route('series.show', $series->slug);
    }

    /**
     * Display the specified resource.
     */
    public function show(Series $series): Response
    {
        $series->load([
            'studio',
        ])->loadCount([
            'movies',
        ]);

        $series->setRelation('movies', $series->getMovies((int) request()->query('per_page', '25')));

        return Inertia::render('Item/Show', [
            'item' => $series,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Series $series): Response
    {
        $series->load([
            'studio',
        ]);

        $studioSearch = request()->query('studio', null);

        return Inertia::render('Item/Edit', [
            'item' => $series,
            'studios' => function () use ($studioSearch, $series) {
                // If studio search is set and is a string, return the results
                if ($studioSearch && is_string($studioSearch)) {
                    return collect(Studio::search($studioSearch)->get());
                }

                // If the item has a studio, return it
                if ($series->studio) {
                    return collect([$series->studio]);
                }

                return collect();
            },
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSeriesRequest $request, Series $series): RedirectResponse
    {
        $validatedData = $request->validated();

        $locale = App::getLocale();

        $series->update([
            'title' => [
                $locale => $validatedData['title'],
                'ja-JP' => $validatedData['original_title'],
            ],
            'studio_id' => $validatedData['studioId'] ?? null,
        ]);

        return redirect()->route('series.show', $series->slug);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Series $series): RedirectResponse
    {
        $series->delete();

        return redirect()->route('series.index');
    }
}
