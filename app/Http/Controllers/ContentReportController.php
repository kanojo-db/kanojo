<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\ReportType;
use App\Models\ContentReport;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ContentReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Movie $movie): RedirectResponse|Response
    {
        /** @var User|null */
        $user = Auth::user();

        if (Auth::check() && $user !== null) {
            // If the user does not have permission to view the reports, redirect them to the movie page
           $user->load('roles');

            if (! $user->roles->contains('name', 'admin') || ! $user->roles->contains('name', 'moderator')) {
                return redirect()->route('movies.show', $movie);
            }

            $movie->load(['reports', 'reports.reporter', 'media']);

            return Inertia::render('Movie/Reports', [
                'movie' => $movie,
            ]);
        }

        return redirect()->route('movies.show', $movie);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Movie $movie): RedirectResponse
    {
        /** @var User|null */
        $user = Auth::user();

        if (Auth::check() && $user !== null) {
            $request->validate([
                'type' => 'required|string',
                'message' => 'required|string',
                'public' => 'required|boolean',
            ]);

            /** @var string */
            $type = $request->input('type');
            $reportType = ReportType::from($type);

            ContentReport::create([
                'type' => $reportType,
                'reporter_id' => $user->id,
                'reporter' => $user,
                'reportable_id' => $movie->id,
                'reportable_type' => $movie->getMorphClass(),
                'message' => $request->input('message'),
                'public' => $request->input('public'),
            ]);

            return back();
        }

        return back();
    }
}
