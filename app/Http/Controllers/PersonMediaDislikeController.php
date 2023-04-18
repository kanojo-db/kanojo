<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\KanojoMedia;
use App\Models\Person;
use App\Models\User;
use Cog\Laravel\Love\ReactionType\Models\ReactionType;
use Illuminate\Support\Facades\Auth;

class PersonMediaDislikeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Person $person, KanojoMedia $media): void
    {
        /** @var User|null */
        $user = Auth::user();

        if (Auth::check() && $user !== null) {
            $reacterFacade = $user->viaLoveReacter();
            $dislikeType = ReactionType::fromName('dislike');

            $hasDisliked = $reacterFacade->hasReactedTo($media, $dislikeType->getName());

            if ($reacterFacade->hasReactedTo($media)) {
                $reacterFacade->unreactTo($media, $dislikeType->getName());
            } else {
                $reacterFacade->reactTo($media, $dislikeType->getName());
            }

            redirect()->back();
        }
    }
}
