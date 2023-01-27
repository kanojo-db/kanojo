<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Person;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function __invoke(Request $request): \Inertia\Response
    {
        $models_results = Person::search($request->q)->paginate(25);
        $movies_results = Movie::search($request->q)->paginate(25);

        $movies_results->load('media');
        $models_results->load('media');

        return Inertia::render('Search', ['movies_results' => $movies_results->appends(request()->query()), 'models_results' => $models_results->appends(request()->query())]);
    }
}
