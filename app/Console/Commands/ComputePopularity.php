<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\ComputePopularityJob;
use Illuminate\Console\Command;

class ComputePopularity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:compute-popularity
            {model : Class name of model to update}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $class = $this->argument('model');

        $model = new $class;

        $this->info('Updating popularity for '.$class);

        ComputePopularityJob::dispatchSync($model::class);

        $this->info('Done.');
    }
}
