<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Person;
use Inertia\Inertia;
use Inertia\Response;

class PersonHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Person $model): Response
    {
        return Inertia::render('Item/History', [
            'item' => function () use ($model) {
                $model->load([
                    'media',
                ]);

                $model->setRelation('audits', $model->audits()->latest()->paginate(10));

                return $model;
            },
        ]);
    }
}
