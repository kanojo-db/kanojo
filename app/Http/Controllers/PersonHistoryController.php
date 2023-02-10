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
    public function index($id): Response
    {
        $person = Person::with(['media'])->findOrFail($id);

        return Inertia::render('Person/History', ['model' => $person, 'history' => $person->audits()->orderBy('updated_at', 'DESC')->get()]);
    }
}
