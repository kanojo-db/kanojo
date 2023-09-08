<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ComputeModelsAgesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Movie $movie,
    ) {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // First ensure that the models are loaded
        $this->movie->load('models');

        // If the movie has no models, we can end the job here
        if ($this->movie->models()->count() === 0) {
            return;
        }

        // If the movie has no release date, we set the age of all models to null
        if ($this->movie->release_date === null) {
            $this->movie->models()->updateExistingPivot($this->movie->models->pluck('id'), ['age' => null]);

            return;
        }

        // When we have a release date, we can compute the age of each model
        foreach ($this->movie->models as $model) {
            // Make sure we reset the age to null if the model has no birthdate
            if ($model->birthdate === null) {
                $this->movie->models()->updateExistingPivot($model->id, ['age' => null]);

                continue;
            }

            $age = Carbon::parse($this->movie->release_date)->diffInYears(Carbon::parse($model->birthdate));

            $this->movie->models()->updateExistingPivot($model->id, ['age' => $age]);
        }
    }
}
