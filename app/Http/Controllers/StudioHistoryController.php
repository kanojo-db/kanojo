<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Studio;
use Inertia\Inertia;
use Inertia\Response;

class StudioHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Studio $studio): Response
    {
        return Inertia::render('Item/History', [
            'item' => function () use ($studio) {
                $studio->load([
                    'media',
                ]);

                $studio->setRelation('audits', $studio->audits()->latest()->paginate(10));

                return $studio;
            },
        ]);
    }
}
