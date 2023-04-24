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
        if ($this->movie->release_date && $this->movie->models()->count() > 0) {
            foreach ($this->movie->models as $model) {
                if ($model->birthdate === null) {
                    continue;
                }

                $age = Carbon::parse($this->movie->release_date)->diffInYears(Carbon::parse($model->birthdate));

                $this->movie->models()->updateExistingPivot($model->id, ['age' => $age]);
            }
        }
    }
}
