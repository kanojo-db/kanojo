<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\User;
use Cog\Laravel\Love\ReactionType\Models\ReactionType;
use Illuminate\Support\Facades\Auth;

class MovieLikeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Movie $movie): void
    {
        /** @var User|null */
        $user = Auth::user();

        if (Auth::check() && $user !== null) {
            $reacterFacade = $user->viaLoveReacter();
            $likeType = ReactionType::fromName('Like');

            $hasLiked = $reacterFacade->hasReactedTo($movie, $likeType->getName());

            if ($reacterFacade->hasReactedTo($movie)) {
                $reacterFacade->unreactTo($movie, $likeType->getName());

                if ($hasLiked) {
                    return;
                }
            }

            $reacterFacade->reactTo($movie, $likeType->getName());
        }
    }
}
