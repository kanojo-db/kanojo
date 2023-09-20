<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\MovieUpdated;
use App\Events\PersonUpdated;
use App\Jobs\GenerateSocialImage;
use Illuminate\Events\Dispatcher;

class SocialImageUpdateSubscriber
{
    /**
     * Handle MovieUpdated events.
     */
    public function onMovieUpdate(MovieUpdated $event): void
    {
        GenerateSocialImage::dispatch($event->movie);
    }

    /**
     * Handle PersonUpdated events.
     */
    public function onPersonUpdate(PersonUpdated $event): void
    {
        GenerateSocialImage::dispatch($event->person);
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            'App\Events\MovieCreated',
            'App\Listeners\SocialImageUpdateSubscriber@onMovieUpdate'
        );

        $events->listen(
            'App\Events\MovieUpdated',
            'App\Listeners\SocialImageUpdateSubscriber@onMovieUpdate'
        );

        $events->listen(
            'App\Events\PersonCreated',
            'App\Listeners\SocialImageUpdateSubscriber@onPersonUpdate'
        );

        $events->listen(
            'App\Events\PersonUpdated',
            'App\Listeners\SocialImageUpdateSubscriber@onPersonUpdate'
        );
    }
}
