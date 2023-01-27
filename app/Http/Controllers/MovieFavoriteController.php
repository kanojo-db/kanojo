<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Support\Facades\Auth;

class MovieFavoriteController extends Controller
{
    public function store(Movie $movie)
    {
        // If logged in, add to favorites
        if (Auth::check()) {
            $user = Auth::user();

            $user->favorites()->attach($movie);

            return back();
        }

        // If not logged in, return 401
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function destroy(Movie $movie)
    {
        // If logged in, remove from favorites
        if (Auth::check()) {
            $user = Auth::user();

            $user->favorites()->detach($movie);

            return back();
        }

        return back();
    }
}
