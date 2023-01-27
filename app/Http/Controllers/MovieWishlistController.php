<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Support\Facades\Auth;

class MovieWishlistController extends Controller
{
    public function store(Movie $movie)
    {
        // If logged in, add to favorites
        if (Auth::check()) {
            $user = Auth::user();

            $user->wishlist()->attach($movie);

            return back();
        }

        return back();
    }

    public function destroy(Movie $movie)
    {
        // If logged in, remove from favorites
        if (Auth::check()) {
            $user = Auth::user();

            $user->wishlist()->detach($movie);

            return back();
        }

        return back();
    }
}
