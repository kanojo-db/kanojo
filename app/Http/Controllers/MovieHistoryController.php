<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Movie;
use Inertia\Inertia;

class MovieHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Movie $movie): \Inertia\Response
    {
        $movie->load('media');

        return Inertia::render('Movie/History', ['movie' => $movie, 'history' => $movie->audits()->orderBy('updated_at', 'DESC')->get()]);
    }
}
