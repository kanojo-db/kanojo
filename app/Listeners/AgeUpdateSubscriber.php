<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\MovieUpdated;
use App\Events\PersonUpdated;
use App\Jobs\ComputeModelsAgesJob;
use Illuminate\Events\Dispatcher;

class AgeUpdateSubscriber
{
    /**
     * Handle MovieUpdated events.
     */
    public function onMovieUpdate(MovieUpdated $event): void
    {
        // Check if we have models on the movie first, to avoid unnecessary jobs
        if ($event->movie->models()->count() === 0) {
            return;
        }

        // Queue a job to update the age of the models for this movie
        ComputeModelsAgesJob::dispatch($event->movie);
    }

    /**
     * Handle PersonUpdated events.
     */
    public function onPersonUpdate(PersonUpdated $event): void
    {
        // Check if the person has any movies, to avoid unnecessary jobs
        if ($event->person->movies()->count() === 0) {
            return;
        }

        // If the movies relationship is not loaded, load it
        if (! $event->person->relationLoaded('movies')) {
            $event->person->load('movies');
        }

        // Queue a job to update the age of the models for each movie
        foreach ($event->person->movies as $movie) {
            ComputeModelsAgesJob::dispatch($movie);
        }
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            'App\Events\MovieUpdated',
            'App\Listeners\AgeUpdateSubscriber@onMovieUpdate'
        );

        $events->listen(
            'App\Events\PersonUpdated',
            'App\Listeners\AgeUpdateSubscriber@onPersonUpdate'
        );
    }
}
