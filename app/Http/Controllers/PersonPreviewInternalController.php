<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PersonPreviewInternalController extends Controller
{
    /**
     * Internal route to render the preview.
     */
    public function show(Request $request, Person $model): Response
    {
        $model->load(['media', 'movies' => function ($query) {
            $query->take(5)->orderBy('release_date', 'desc');
        }]);

        return Inertia::render('Preview/Person', [
            'model' => $model,
            'moviesCount' => $model->movies()->count(),
        ]);
    }
}
