<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PersonPreviewInternalController extends Controller
{
    /**
     * Internal route to render the preview.
     */
    public function show(Request $request, Person $model)
    {
        $model->load(['media', 'movies' => function ($query) {
            $query->take(5)->orderBy('release_date', 'desc');
        }]);

        // Get a count of all the movies the person has been in.
        $model->movie_count = $model->movies()->count();

        return Inertia::render('Preview/Person', [
            'model' => $model,
        ]);
    }
}
