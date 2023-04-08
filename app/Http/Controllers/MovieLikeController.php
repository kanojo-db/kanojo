<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Cog\Laravel\Love\ReactionType\Models\ReactionType;

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
