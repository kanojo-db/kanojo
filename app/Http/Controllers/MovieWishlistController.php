<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class MovieWishlistController extends Controller
{
    public function store(Movie $movie): RedirectResponse
    {
        /** @var User|null */
        $user = Auth::user();

        // If logged in, add to favorites
        if (Auth::check() && $user !== null) {
            $user->wishlist()->attach($movie);

            return back();
        }

        return back();
    }

    public function destroy(Movie $movie): RedirectResponse
    {
        /** @var User|null */
        $user = Auth::user();

        // If logged in, remove from favorites
        if (Auth::check() && $user !== null) {
            $user->wishlist()->detach($movie);

            return back();
        }

        return back();
    }
}
