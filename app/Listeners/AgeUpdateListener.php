<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Console\Commands\ComputeModelsAges;
use App\Events\MovieUpdated;
use App\Events\PersonUpdated;

class AgeUpdateListener
{
    /**
     * Handle MovieUpdated events.
     */
    public function onMovieUpdate(MovieUpdated $event)
    {
        // Queue a job to update the age of the models for this movie
        ComputeModelsAges::dispatch($event->movie);
    }

    /**
     * Handle PersonUpdated events.
     */
    public function onPersonUpdate(PersonUpdated $event)
    {
        // If the movies relationship is not loaded, load it
        if (! $event->person->relationLoaded('movies')) {
            $event->person->load('movies');
        }

        // Queue a job to update the age of the models for each movie
        foreach ($event->person->movies as $movie) {
            ComputeModelsAges::dispatch($movie);
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\MovieUpdated',
            'App\Listeners\AgeUpdateListener@onMovieUpdate'
        );

        $events->listen(
            'App\Events\PersonUpdated',
            'App\Listeners\AgeUpdateListener@onPersonUpdate'
        );
    }
}
