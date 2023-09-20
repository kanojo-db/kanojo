<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Alias;
use App\Models\Person;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PersonAliasController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Person::class, 'model');
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Person $model): RedirectResponse
    {
        // Validate request
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $model->aliases()->create([
            'alias' => $validatedData['name'],
        ]);

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     * This is MAINLY used to lock/unlock a model.
     */
    public function update(Person $model, Alias $alias): RedirectResponse
    {
        $alias->update([
            'locked' => ! $alias->locked,
        ]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Person $model, Alias $alias): RedirectResponse
    {
        // @phpstan-ignore-next-line -- This seems like a bug?
        $model->aliases()->delete($alias);

        return redirect()->back();
    }
}
