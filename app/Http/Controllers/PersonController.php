<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App;
use App\Http\Requests\UpdatePersonRequest;
use App\Models\Country;
use App\Models\Gender;
use App\Models\Person;
use App\Sorts\RelationshipCountSort;
use Carbon\Carbon;
use Crawler;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class PersonController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Person::class, 'model');
        $this->middleware('auth')->except(['show', 'index']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('PersonIndex', [
            'models' => function () {
                return QueryBuilder::for(Person::class)
                    ->defaultSort('-created_at')
                    ->allowedSorts([
                        'created_at',
                        'birthdate',
                        'height',
                        'bust',
                        'waist',
                        'hip',
                        'popularity',
                        AllowedSort::custom('movies', new RelationshipCountSort(), 'movies'),
                    ])
                    ->allowedFilters([
                        AllowedFilter::scope('born', 'born_between'),
                        'height',
                        'bust',
                        'waist',
                        'hip',
                    ])
                    ->with('media')
                    ->withCount('movies')
                    ->paginate(25)
                    ->appends(request()->query());
            },
            'birthCounts' => function (): Collection {
                /** @var \Illuminate\Database\Query\Expression */
                $expression = DB::raw('value');

                return Person::select([
                    DB::raw('YEAR(birthdate) AS value'),
                    DB::raw('COUNT(*) AS count'),
                ])
                    ->where('birthdate', '!=', null)
                    ->groupBy(
                        $expression
                    )
                    ->orderBy(
                        $expression
                    )
                    ->get();
            },
            'heightCounts' => function (): Collection {
                return Person::select([
                    DB::raw('height as value'),
                    DB::raw('COUNT(*) AS count'),
                ])
                    ->where('height', '!=', null)
                    ->groupBy('value')
                    ->orderBy('value')
                    ->get();
            },
            'bustCounts' => function (): Collection {
                return Person::select([
                    DB::raw('bust as value'),
                    DB::raw('COUNT(*) AS count'),
                ])
                    ->where('bust', '!=', null)
                    ->groupBy('value')
                    ->orderBy('value')
                    ->get();
            },
            'waistCounts' => function (): Collection {
                return Person::select([
                    DB::raw('waist as value'),
                    DB::raw('COUNT(*) AS count'),
                ])
                    ->where('waist', '!=', null)
                    ->groupBy('value')
                    ->orderBy('value')
                    ->get();
            },
            'hipCounts' => function (): Collection {
                return Person::select([
                    DB::raw('hip as value'),
                    DB::raw('COUNT(*) AS count'),
                ])
                    ->where('hip', '!=', null)
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
        return Inertia::render('Item/Create', [
            'type' => 'person',
            'country' => Country::all(),
            'gender' => Gender::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UpdatePersonRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        $locale = App::getLocale();

        $person = Person::create([
            'name' => [
                $locale => $validatedData['name'],
                'ja-JP' => $validatedData['original_name'],
            ],
            'birthdate' => $validatedData['birthdate'],
            'career_start' => $validatedData['career_start'],
            'career_end' => $validatedData['career_end'],
            'blood_type' => $validatedData['blood_type'],
            'cup_size' => $validatedData['cup_size'],
            'height' => $validatedData['height'],
            'bust' => $validatedData['bust'],
            'waist' => $validatedData['waist'],
            'hip' => $validatedData['hip'],
            'country_id' => $validatedData['country_id'] ?? null,
        ]);

        return Redirect::route('models.show', $person->slug);
    }

    /**
     * Display the specified resource.
     */
    public function show(Person $model): Response
    {
        return Inertia::render('Item/Show', [
            'item' => function () use ($model): Person {
                $model->load([
                    'media',
                    'country',
                    'aliases',
                    'movies.media',
                    'movies.type',
                    'movies.loveReactant.reactions.reacter.reacterable',
                    'movies.loveReactant.reactions.type',
                    'movies.loveReactant.reactionCounters',
                    'movies.loveReactant.reactionTotal',
                ]);

                $model->setRelation('movies', $model->getStarringMovies((int) request()->query('per_page', 25)));

                // Don't log visits from bots, as it may skew the popularity score
                if (Crawler::isCrawler()) {
                    // Check if the user is an admin or mod, and if so, don't log the visit.
                    // We go around the site a lot, and it would skew the popularity score.
                    if (! auth()->user()?->hasAnyRole(['admin', 'moderator'])) {
                        $model->visit();
                    }
                }

                return $model;
            },
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Person $model): Response
    {
        $model->load(['media', 'gender', 'aliases']);

        return Inertia::render('Item/Edit', [
            'item' => $model,
            'countries' => fn () => Country::all(),
            'genders' => fn () => Gender::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonRequest $request, Person $model): RedirectResponse
    {
        /** @var array{birthdate: string|null, blood_type: string|null, career_end: string|null, career_start: string|null, country: string|null, cup_size: string|null, height: string|null, hip: string|null, name: string|null, original_name: string, blood_type: string, bust: string|null, waist: string|null} */
        $validatedData = $request->validated();

        $locale = App::getLocale();

        $model->update([
            'name' => [
                $locale => $validatedData['name'],
                'ja-JP' => $validatedData['original_name'],
            ],
            'birthdate' => $validatedData['birthdate'],
            'career_start' => $validatedData['career_start'],
            'career_end' => $validatedData['career_end'],
            'blood_type' => $validatedData['blood_type'],
            'cup_size' => $validatedData['cup_size'],
            'height' => $validatedData['height'],
            'bust' => $validatedData['bust'],
            'waist' => $validatedData['waist'],
            'hip' => $validatedData['hip'],
            'country_id' => $validatedData['country_id'] ?? null,
        ]);

        $shouldSave = false;

        // If gender_id was changed, update the relationship
        if ($validatedData['gender_id']) {
            $gender = Gender::find($validatedData['gender_id']);

            $model->gender()->associate($gender);

            $shouldSave = true;
        }

        // Avoid saving if no relationships were changed
        if ($shouldSave) {
            $model->save();
        }

        // If birthdate was changed, update all movies with this person
        // TODO: This should be a job.
        if ($model->wasChanged('birthdate')) {
            $model->load('movies');

            foreach ($model->movies as $movie) {
                // If the release date is not set, skip
                if (! $movie->release_date) {
                    continue;
                }

                // If the release date is before the birthdate, skip
                if (Carbon::parse($movie->release_date)->isBefore(Carbon::parse($model->birthdate))) {
                    continue;
                }

                $age = Carbon::parse($movie->release_date)->diffInYears(Carbon::parse($model->birthdate));
                $model->movies()->updateExistingPivot($movie->id, ['age' => $age]);
            }
        }

        return Redirect::route('models.show', $model);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Person $model): RedirectResponse
    {
        $model->delete();

        return redirect()->route('models.index');
    }
}
