<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Series;
use App\Transformers\SeriesIDTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class ChangesSeriesController extends Controller
{
    /**
     * Series
     *
     * Get a list of all series IDs updated in the given time range. Defaults to the last 24 hours.
     * The maximum date range allowed is 14 days.
     *
     * @group Changes
     *
     * @queryParam start_date string The start date of the changes list. Example: 2021-01-01
     * @queryParam end_date string The end date of the changes list. Example: 2021-01-01
     * @queryParam page integer Specify which page to query. Defaults to 1. Example: 2
     */
    public function index(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'page' => ['nullable', 'integer'],
        ]);

        // Allow a maximum of 14 days
        if (isset($validatedData['start_date'], $validatedData['end_date'])) {
            $startDate = \Carbon\Carbon::parse($validatedData['start_date']);
            $endDate = \Carbon\Carbon::parse($validatedData['end_date']);

            if ($startDate->diffInDays($endDate) > 14) {
                abort(400, 'The maximum allowed date range is 14 days.');
            }
        }

        // If only one date is given, default to a 24 hour range around that date
        if (isset($validatedData['start_date']) && ! isset($validatedData['end_date'])) {
            $validatedData['end_date'] = \Carbon\Carbon::parse($validatedData['start_date'])->addDay()->format('Y-m-d');
        } elseif (! isset($validatedData['start_date']) && isset($validatedData['end_date'])) {
            $validatedData['start_date'] = \Carbon\Carbon::parse($validatedData['end_date'])->subDay()->format('Y-m-d');
        }

        $paginator = Series::query()
            ->whereBetween('updated_at', [
                $validatedData['start_date'] ?? now()->subDay()->format('Y-m-d'),
                $validatedData['end_date'] ?? now()->format('Y-m-d'),
            ])
            ->paginate(25);

        return response()->json(
            fractal()
                ->collection($paginator->getCollection())
                ->transformWith(new SeriesIDTransformer())
                ->paginateWith(new IlluminatePaginatorAdapter($paginator))
                ->toArray()
        );
    }
}
