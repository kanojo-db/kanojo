<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Cog\Laravel\Love\ReactionType\Models\ReactionType;

class MovieDislikeController extends Controller
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
