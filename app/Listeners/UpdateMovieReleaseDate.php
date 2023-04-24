<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\MovieVersionUpdated;
use App\Models\Movie;
use Carbon\Carbon;

class UpdateMovieReleaseDate
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
    public function handle(MovieVersionUpdated $event): void
    {
        $movie = Movie::find($event->movieVersion->movie_id);

        if ($movie === null) {
            return;
        }

        if ($movie->release_date === null) {
            $movie->release_date = $event->movieVersion->release_date;

            $movie->save();

            return;
        }

        if (
            Carbon::parse($event->movieVersion->release_date)->lt(
                Carbon::parse($movie->release_date)
            )
        ) {
            $movie->release_date = $event->movieVersion->release_date;

            $movie->save();

            return;
        }
    }
}
