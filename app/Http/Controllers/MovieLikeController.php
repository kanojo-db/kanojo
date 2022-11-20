<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Http\Requests\StoreMovieLikeRequest;
use Cog\Laravel\Love\ReactionType\Models\ReactionType;
use Illuminate\Support\Facades\Auth;

class MovieLikeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @return void
     */
    public function store(Movie $movie)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $reacterFacade = $user->viaLoveReacter();

            $hasLiked = $reacterFacade->hasReactedTo($movie, 'Like');

            if ($reacterFacade->hasReactedTo($movie)) {
                $reacterFacade->unreactTo($movie, 'Like');

                if ($hasLiked) {
                    return;
                }
            }

            $reacterFacade->reactTo($movie, 'Like');
        }
    }
}
