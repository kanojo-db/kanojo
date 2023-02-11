<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Database\Eloquent\Collection;
use Inertia\Inertia;
use Inertia\Response;

class PersonHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Person $model): Response
    {
        return Inertia::render('Person/History', [
            'model' => function() use ($model): Person {
                $model->load(['media']);

                return $model;
            },
            'history' => function() use ($model): Collection {
                return $model->audits()->orderBy('updated_at', 'DESC')->get();
            }
        ]);
    }
}
