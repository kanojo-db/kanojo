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
        $studio->load(['media', 'audits' => function ($query) {
            $query->latest()->paginate(10);
        }]);

        return Inertia::render('Item/History', ['item' => $studio]);
    }
}
