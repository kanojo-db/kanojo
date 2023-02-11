<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Person;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SearchController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $request->validate([
            'q' => ['required', 'string'],
        ]);

        $validatedData = $request->validated();

        return Inertia::render('Search', [
            'movies_results' => function () use ($validatedData): LengthAwarePaginator {
                return Person::with(['media'])->search($validatedData->q)->paginate(25);
            },
            'models_results' => function () use ($validatedData): LengthAwarePaginator {
                return Movie::with(['media'])->search($validatedData->q)->paginate(25);
            },
        ]);
    }
}
