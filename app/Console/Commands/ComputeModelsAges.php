<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\ComputeModelsAgesJob;
use App\Models\Movie;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ComputeModelsAges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kanojo:refresh-ages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh all ages for models starring in movies';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        Movie::whereHas('models', function (Builder $query) {
            $query->whereNot('birthdate', null);
        })->whereNot('release_date', null)
            ->chunkById(100, function (Collection $movies) {
                /** @param  Movie  $movie */
                $movies->each(function ($movie) {
                    $this->info("Dispatching job for movie {$movie->id}");

                    ComputeModelsAgesJob::dispatch($movie);
                });
            });

        return Command::SUCCESS;
    }
}
