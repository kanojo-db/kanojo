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
        return Inertia::render('Item/History', [
            'item' => function () use ($series) {
                $series->setRelation('audits', $series->audits()->latest()->paginate(10));

                return $series;
            },
        ]);
    }
}
