<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\ComputeModelsAgesJob;
use App\Models\Person;
use Illuminate\Console\Command;

class ComputeModelAges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kanojo:refresh-ages {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh all ages for the given model';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        /** @var Person $model */
        $model = Person::with('movies')->findOrFail($this->argument('model'));

        $model->movies->each(function ($movie) {
            $this->info("Dispatching job for movie {$movie->id}");

            ComputeModelsAgesJob::dispatchSync($movie);
        });

        return Command::SUCCESS;
    }
}
