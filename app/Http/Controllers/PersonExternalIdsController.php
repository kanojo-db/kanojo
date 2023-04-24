<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PersonExternalIdsUpdateRequest;
use App\Models\Person;
use Illuminate\Http\RedirectResponse;

class PersonExternalIdsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(PersonExternalIdsUpdateRequest $request, Person $model): RedirectResponse
    {
        $validatedData = $request->validated();

        $model->update($validatedData);

        return redirect()->route('models.show', $model);
    }
}
