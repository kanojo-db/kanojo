<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

class SlugUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slug:update-all
            {model : Class name of model to update} {--id= : ID of model to update}';

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

        // Check that $class is a string and that it extends Model.
        if (! is_string($class) || ! is_subclass_of($class, Model::class)) {
            $this->error('Invalid model class.');

            return;
        }

        $id = $this->option('id');

        if (! is_numeric($id)) {
            $this->error('Invalid ID.');

            return;
        }

        /** @var Model */
        $model = new $class;

        $this->info('Updating slugs for '.$class.' with ID '.$id);

        $model::findOrFail($this->option('id'))->update([
            'slug' => null,
        ]);

        $this->info('Done.');
    }
}
