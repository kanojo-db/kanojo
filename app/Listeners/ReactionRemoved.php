<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Jobs\SortMediaCollection;
use App\Models\KanojoMedia;
use Cog\Laravel\Love\Reaction\Events\ReactionHasBeenRemoved;

class ReactionRemoved
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
    public function handle(ReactionHasBeenRemoved $event): void
    {
        $reaction = $event->getReaction();

        $reactable = $reaction->getReactant()->getReactable();

        if ($reactable instanceof KanojoMedia) {
            SortMediaCollection::dispatch($reactable);
        }
    }
}
