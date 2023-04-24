<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ComputePopularityJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var mixed
     */
    private $model;

    /**
     * Create a new job instance.
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $model = new $this->model;

        $model::chunkById(100, function ($items) use ($model) {
            foreach ($items as $item) {
                $model::withoutTimestamps(function () use ($item) {
                    $score = $item->computePopularity();

                    Log::info('Updating '.$item->id.' with score '.$score);

                    $item->popularity = $score;

                    $item->save();
                });
            }
        });
    }
}
