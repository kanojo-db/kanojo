<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\ReportType;
use App\Models\ContentReport;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ContentReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(Movie $movie)
    {
        // If the user is not logged in, redirect them to the movie page
        if (! Auth::check()) {
            return redirect()->route('movies.show', $movie);
        }

        // If the user does not have permission to view the reports, redirect them to the movie page
        Auth::user()->load('roles');

        /*if (! Auth::user()->roles->contains('name', 'admin') || ! Auth::user()->roles->contains('name', 'moderator')) {
            return redirect()->route('movies.show', $movie);
        }*/

        $movie->load(['reports', 'reports.reporter', 'media']);

        return Inertia::render('Movie/Reports', [
            'movie' => $movie,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Movie $movie)
    {
        $request->validate([
            'type' => 'required|string',
            'message' => 'required|string',
            'public' => 'required|boolean',
        ]);

        // type is a string, but we want to store it as an enum
        $type = $request->input('type');
        $reportType = ReportType::from($type);

        ContentReport::create([
            'type' => $reportType,
            'reporter_id' => Auth::user()->id,
            'reporter' => Auth::user(),
            'reportable_id' => $movie->id,
            'reportable_type' => $movie->getMorphClass(),
            'message' => $request->input('message'),
            'public' => $request->input('public'),
        ]);

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContentReport  $contentReport
     * @return \Inertia\Response
     */
    public function show(ContentReport $contentReport)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContentReport  $contentReport
     * @return \Inertia\Response
     */
    public function edit(ContentReport $contentReport)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContentReport  $contentReport
     * @return \Inertia\Response
     */
    public function update(Request $request, ContentReport $contentReport)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContentReport  $contentReport
     * @return \Inertia\Response
     */
    public function destroy(ContentReport $contentReport)
    {

    }
}
