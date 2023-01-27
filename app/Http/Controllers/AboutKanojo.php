<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Person;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\Tags\Tag;

class AboutKanojo extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        $movie_per_year_count = Movie::select(
            DB::raw('YEAR(release_date) AS value'),
            DB::raw('COUNT(*) AS count')
        )
        ->where('release_date', '!=', null)
        ->groupBy(
            DB::raw('value')
        )
        ->orderBy(
            DB::raw('value')
        )
        ->get();

        $birth_counts = Person::select(
            DB::raw('YEAR(birthdate) AS value'),
            DB::raw('COUNT(*) AS count')
        )
        ->where('birthdate', '!=', null)
        ->groupBy(
            DB::raw('value')
        )
        ->orderBy(
            DB::raw('value')
        )
        ->get();

        $height_counts = Person::select(
            DB::raw('height as value'),
            DB::raw('COUNT(*) AS count')
        )
        ->where('height', '!=', null)
        ->groupBy('value')
        ->orderBy('value')
        ->get();

        $bust_counts = Person::select(
            DB::raw('bust as value'),
            DB::raw('COUNT(*) AS count')
        )
        ->where('bust', '!=', null)
        ->groupBy('value')
        ->orderBy('value')
        ->get();

        $waist_counts = Person::select(
            DB::raw('waist as value'),
            DB::raw('COUNT(*) AS count')
        )
        ->where('waist', '!=', null)
        ->groupBy('value')
        ->orderBy('value')
        ->get();

        $hip_counts = Person::select(
            DB::raw('hip as value'),
            DB::raw('COUNT(*) AS count')
        )
        ->where('hip', '!=', null)
        ->groupBy('value')
        ->orderBy('value')
        ->get();

        $cup_counts = Person::select(
            DB::raw('cup_size as value'),
            DB::raw('COUNT(*) AS count')
        )
        ->where('cup_size', '!=', null)
        ->groupBy('value')
        ->orderBy('value')
        ->get();

        $average_movies_per_model = Person::has('movies')->withCount('movies')->get()->avg('movies_count');

        return Inertia::render('About', [
            'movieCount' => Movie::count(),
            'modelCount' => Person::count(),
            'tagCount' => Tag::count(),
            'moviePerYearCount' => $movie_per_year_count,
            'birthCounts' => $birth_counts,
            'heightCounts' => $height_counts,
            'bustCounts' => $bust_counts,
            'waistCounts' => $waist_counts,
            'hipCounts' => $hip_counts,
            'cupCounts' => $cup_counts,
            'averageMoviesPerModel' => $average_movies_per_model,
        ]);
    }
}
