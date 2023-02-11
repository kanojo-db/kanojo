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
        /** @var User|null */
        $user = Auth::user();

        // If logged in, add to favorites
        if (Auth::check() && $user !== null) {
            $user->favorites()->attach($movie);

            return back();
        }

        // If not logged in, return 401
        abort(401, 'User not logged in');
    }

    public function destroy(Movie $movie): RedirectResponse
    {
        /** @var User|null */
        $user = Auth::user();

        // If logged in, remove from favorites
        if (Auth::check() && $user !== null) {

            $user->favorites()->detach($movie);

            return back();
        }

        abort(401, 'User not logged in');
    }
}
