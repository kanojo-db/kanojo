<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ContentReportRequest;
use App\Http\Requests\ContentReportUpdateRequest;
use App\Models\ContentReport;
use App\Models\Person;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PersonContentReportController extends Controller
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
    public function index(Person $model): RedirectResponse|Response
    {
        $model->load(['media']);

        $model->setRelation('reports', $model->reports()->with('reporter')->paginate(10));

        return Inertia::render('Item/Reports', [
            'item' => $model,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContentReportRequest $request, Person $model): RedirectResponse
    {
        /** @var array{type: string, message: string|null, public: bool} */
        $validatedData = $request->validated();

        $model->reports()->create([
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
    public function update(ContentReportUpdateRequest $request, Person $model, ContentReport $report): RedirectResponse
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
    public function destroy(Person $model, ContentReport $report): RedirectResponse
    {
        $reportItem = ContentReport::findOrFail($report);

        $reportItem->delete();

        return back();
    }
}
