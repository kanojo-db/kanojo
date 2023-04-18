<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Jobs\SortMediaCollection;
use App\Models\KanojoMedia;
use Cog\Laravel\Love\Reaction\Events\ReactionHasBeenAdded;
use Log;

class MediaReactionAdded
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(ReactionHasBeenAdded $event): void
    {
        $reaction = $event->getReaction();

        /** @var KanojoMedia */
        $media = $reaction->getReactant()->getReactable();

        // Queue a SortMediaCollection for this media
        SortMediaCollection::dispatch($media);
    }
}
