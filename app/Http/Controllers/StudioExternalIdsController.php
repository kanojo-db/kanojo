<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StudioExternalIdsUpdateRequest;
use App\Models\Studio;
use Illuminate\Http\RedirectResponse;

class StudioExternalIdsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StudioExternalIdsUpdateRequest $request, Studio $studio): RedirectResponse
    {
        $validatedData = $request->validated();

        $studio->update($validatedData);

        return redirect()->route('studios.show', $studio);
    }
}
