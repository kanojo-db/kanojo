<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class MovieFavoriteController extends Controller
{
    public function store(Movie $movie): RedirectResponse
    {
        // Make sure we are logged in and have permission to add to our collection.
        if (Auth::check() || ! Auth::user()?->can('manage collection')) {
            return redirect()->route('login');
        }

        // At this point, we should be logged in, so we can safely assume that Auth::user() is not null.
        /** @var User */
        $user = Auth::user();

        $user->favorites()->attach($movie);

        return back();
    }

    public function destroy(Movie $movie): RedirectResponse
    {
        // Make sure we are logged in and have permission to add to our collection.
        if (Auth::check() || ! Auth::user()?->can('manage collection')) {
            return redirect()->route('login');
        }

        // At this point, we should be logged in, so we can safely assume that Auth::user() is not null.
        /** @var User */
        $user = Auth::user();

        $user->favorites()->detach($movie);

        return back();
    }
}
