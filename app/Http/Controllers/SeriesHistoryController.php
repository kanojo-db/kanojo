<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Series;
use Inertia\Inertia;
use Inertia\Response;

class SeriesHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Series $series): Response
    {
        $series->load(['audits' => function ($query) {
            $query->latest()->paginate(10);
        }]);

        return Inertia::render('Item/History', ['item' => $series]);
    }
}
