<?php

namespace App\Console\Commands;

use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ComputeAges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kanojo:refreshAllAges';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refersh all ages for models starring in movies';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $movies = Movie::whereHas('models', function ($query) {
            $query->whereNot('birthdate', null);
        })->whereNot('release_date', null)->withoutGlobalScope('filterHidden')->get();

        $movies->each(function ($movie, $key) {
            $movie->models()->each(function ($model, $key) use ($movie) {
                try {
                    $age = Carbon::parse($movie->release_date)->diff(Carbon::parse($model->birthdate))->format('%y');

                    $this->info('Set age to '.$age);

                    $movie->models()->updateExistingPivot($model->id, ['age' => $age]);
                } catch (\Throwable $th) {
                    $this->error($movie->title->jp.': error when setting age for '.$model->name->jp);
                }
            });
        });

        return Command::SUCCESS;
    }
}
