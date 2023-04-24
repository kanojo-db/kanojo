<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ContentReportRequest;
use App\Http\Requests\ContentReportUpdateRequest;
use App\Models\ContentReport;
use App\Models\Studio;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class StudioContentReportController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(ContentReport::class, 'contentReport');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Studio $studio): RedirectResponse|Response
    {
        $studio->load(['media']);

        $studio->setRelation('reports', $studio->reports()->with('reporter')->paginate(10));

        return Inertia::render('Item/Reports', [
            'item' => $studio,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContentReportRequest $request, Studio $studio): RedirectResponse
    {
        /** @var array{type: string, message: string|null, public: bool} */
        $validatedData = $request->validated();

        $studio->reports()->create([
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
    public function update(ContentReportUpdateRequest $request, Studio $studio, ContentReport $report): RedirectResponse
    {
        $reportItem = ContentReport::findOrFail($report);

        /** @var array{status: string} */
        $validatedData = $request->validated();

        $reportItem->update([
            'status' => $validatedData['status'],
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Studio $studio, ContentReport $report): RedirectResponse
    {
        $reportItem = ContentReport::findOrFail($report);

        $reportItem->delete();

        return back();
    }
}
