<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Person;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PersonHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id): \Inertia\Response
    {
        $person = Person::with(['media'])->findOrFail($id);

        return Inertia::render('Person/History', ['model' => $person, 'history' => $person->audits()->orderBy('updated_at','DESC')->get()]);
    }
}
