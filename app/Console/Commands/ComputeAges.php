<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Movie;
use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

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
     */
    public function handle(): int
    {
        $movies = Movie::whereHas('models', function (Builder $query) {
            $query->whereNot('birthdate', null);
        })->whereNot('release_date', null)->get();

        $movies->each(function (Movie $movie) {
            $movie->models()->each(function (Person $model) use ($movie) {
                try {
                    $age = Carbon::parse($movie->release_date)->diff(Carbon::parse($model->birthdate))->format('%y');

                    $this->info('Set age to '.$age);

                    $movie->models()->updateExistingPivot($model->id, ['age' => $age]);
                } catch (\Throwable $th) {
                    $this->error('Error when setting age');
                }
            });
        });

        return Command::SUCCESS;
    }
}
