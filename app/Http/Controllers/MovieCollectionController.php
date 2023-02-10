<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class MovieCollectionController extends Controller
{
    public function store(Movie $movie): RedirectResponse
    {
        /** @var User|null */
        $user = Auth::user();

        if (Auth::check() && $user !== null) {
            $user->collection()->attach($movie);

            return back();
        }

        return back();
    }

    public function destroy(Movie $movie): RedirectResponse
    {
        /** @var User|null */
        $user = Auth::user();

        if (Auth::check() && $user !== null) {
            $user->collection()->detach($movie);

            return back();
        }

        return back();
    }
}
