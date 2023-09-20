<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Movie;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class RefreshReleaseDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refresh-release-dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh earliest release date for movies';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        Movie::whereHas('versions')->with('versions')
            ->chunkById(100, function (Collection $movies) {
                $movies->each(function ($movie) {
                    /** @var Movie $movie */
                    $this->info("Updating earliest release date for movie {$movie->id}");

                    $movie->update([
                        'release_date' => $movie->versions->min('release_date'),
                    ]);
                });
            });

        return Command::SUCCESS;
    }
}
