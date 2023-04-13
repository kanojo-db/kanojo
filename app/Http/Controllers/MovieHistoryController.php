<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Movie;
use Inertia\Inertia;
use Inertia\Response;

class MovieHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Movie $movie): Response
    {
        $movie->load(['media']);

        $movie->load(['audits' => function ($query) {
            $query->latest()->paginate(10);
        }]);

        return Inertia::render('Movie/History', ['movie' => $movie]);
    }
}
