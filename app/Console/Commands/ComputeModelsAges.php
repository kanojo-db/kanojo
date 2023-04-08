<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Movie;
use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class ComputeModelsAges extends Command
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
    protected $description = 'Refresh all ages for models starring in movies';

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
                    $age = Carbon::parse($movie->release_date)->diffInYears(Carbon::parse($model->birthdate));

                    $this->info('Setting age for '.$model->name.' in '.$movie->product_code.' to '.$age);

                    $movie->models()->updateExistingPivot($model->id, ['age' => $age]);
                } catch (\Throwable $th) {
                    $this->error('Error when setting age: '.$th->getMessage());
                }
            });
        });

        return Command::SUCCESS;
    }
}
