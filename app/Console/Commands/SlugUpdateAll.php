<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

class SlugUpdateAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slug:update-all
            {model : Class name of model to update}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update slugs for the specified model.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $class = $this->argument('model');

        /** @var Model */
        $model = new $class;

        $this->info('Updating slugs for '.$class);

        $count = $model::whereNull('slug')->count();

        $model::whereNull('slug')
            ->chunkById(100, function ($models) {
                $models->each(function ($model) {
                    $this->info('Updating '.$model->id);
                    $model->update([
                        'slug' => null,
                    ]);
                });
            });

        $this->info('Processed '.$count.' records.');
    }
}
