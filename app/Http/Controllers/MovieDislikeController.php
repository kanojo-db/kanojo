<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Http\Requests\StoreMovieLikeRequest;
use Cog\Laravel\Love\ReactionType\Models\ReactionType;
use Illuminate\Support\Facades\Auth;

class MovieDislikeController extends Controller
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
            $dislikeType = ReactionType::fromName('Dislike');

            $hasDisliked = $reacterFacade->hasReactedTo($movie, $dislikeType->getName());

            if ($reacterFacade->hasReactedTo($movie)) {
                $reacterFacade->unreactTo($movie, $dislikeType->getName());

                if ($hasDisliked) {
                    return;
                }
            }

            $reacterFacade->reactTo($movie, $dislikeType->getName());
        }
    }
}
