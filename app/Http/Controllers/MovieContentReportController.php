<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ContentReportRequest;
use App\Http\Requests\ContentReportUpdateRequest;
use App\Models\ContentReport;
use App\Models\Movie;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class MovieContentReportController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(ContentReport::class, 'report', [
            'except' => ['index'],
        ]);
        $this->middleware('auth')->except(['index']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Movie $movie): RedirectResponse|Response
    {
        $movie->load(['media']);

        $movie->setRelation('reports', $movie->reports()->visible()->with('reporter')->paginate(10));

        return Inertia::render('Item/Reports', [
            'item' => $movie,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContentReportRequest $request, Movie $movie): RedirectResponse
    {
        /** @var array{type: string, message: string|null, public: bool} */
        $validatedData = $request->validated();

        $movie->reports()->create([
            'type' => $validatedData['type'],
            'message' => $validatedData['message'] ?? null,
            'public' => $validatedData['public'],
            'reporter_id' => $request->user()?->id,
        ]);

        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContentReportUpdateRequest $request, Movie $movie, ContentReport $report): RedirectResponse
    {
        /** @var array{status: string} */
        $validatedData = $request->validated();

        $report->update([
            'status' => $validatedData['status'],
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie, ContentReport $report): RedirectResponse
    {
        $report->delete();

        return back();
    }
}
