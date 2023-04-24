<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudioRequest;
use App\Http\Requests\UpdateStudioRequest;
use App\Models\Studio;
use App\Sorts\RelationshipCountSort;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class StudioController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Studio::class, 'studio');
        $this->middleware('auth')->except(['show', 'index']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('StudioIndex', [
            'studios' => function (): LengthAwarePaginator {
                return QueryBuilder::for(Studio::class)
                    ->allowedSorts([
                        'created_at',
                        'name',
                        'updated_at',
                        AllowedSort::custom('movies', new RelationshipCountSort(), 'movies'),
                    ])
                    ->allowedFilters([
                        'name',
                    ])
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
        return Inertia::render('Item/Create', [
            'type' => 'studio',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudioRequest $request): RedirectResponse
    {
        /** @var array{name: string|null, originalName: string, foundedDate: string|null} */
        $validatedData = $request->validated();

        $locale = App::getLocale();

        $studio = Studio::create([
            'name' => [
                $locale => $validatedData['name'],
                'ja-JP' => $validatedData['originalName'],
            ],
            'founded_date' => $validatedData['foundedDate'],
        ]);

        return Redirect::route('studios.show', $studio->slug);
    }

    /**
     * Display the specified resource.
     */
    public function show(Studio $studio): Response
    {
        $studio->load([
            'series',
        ])->loadCount([
            'movies',
        ])->setRelations([
            'movies' => $studio->getReleasedMovies(),
            'models' => $studio->getMostFeaturedModels(),
            'series' => $studio->getMostActiveSeries(),
        ]);

        return Inertia::render('Item/Show', [
            'item' => $studio,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Studio $studio): Response
    {
        $studio->load(['media']);

        return Inertia::render('Item/Edit', [
            'item' => $studio,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudioRequest $request, Studio $studio): RedirectResponse
    {
        $validatedData = $request->validated();

        // TODO: Allow updating other locales. Maybe through another controller?
        $locale = App::getLocale();

        $studio->update([
            'name' => [
                $locale => $validatedData['name'],
                'ja-JP' => $validatedData['originalName'],
            ],
            'founded_date' => $validatedData['foundedDate'],
        ]);

        return Redirect::route('studios.show', $studio);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Studio $studio): RedirectResponse
    {
        $studio->delete();

        return Redirect::route('studios.index');
    }
}
