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

        return Inertia::render('Item/History', [
            'item' => function () use ($movie) {
                $movie->load([
                    'media',
                ]);

                $movie->setRelation('audits', $movie->audits()->with('user')->latest()->paginate(10));

                return $movie;
            },
        ]);
    }
}
