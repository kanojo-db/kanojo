<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\KanojoMedia;
use App\Models\Movie;
use App\Models\User;
use Cog\Laravel\Love\ReactionType\Models\ReactionType;
use Illuminate\Support\Facades\Auth;

class MovieMediaLikeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Movie $movie, KanojoMedia $medium): void
    {
        /** @var User|null */
        $user = Auth::user();

        if (Auth::check() && $user !== null) {
            $reacterFacade = $user->viaLoveReacter();
            $likeType = ReactionType::fromName('like');

            $hasLiked = $reacterFacade->hasReactedTo($medium, $likeType->getName());

            if ($reacterFacade->hasReactedTo($medium)) {
                $reacterFacade->unreactTo($medium, $likeType->getName());
            } else {
                $reacterFacade->reactTo($medium, $likeType->getName());
            }

            redirect()->back();
        }
    }
}
