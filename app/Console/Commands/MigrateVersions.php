<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\MovieFormat;
use App\Models\Movie;
use App\Models\MovieVersion;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class MigrateVersions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kanojo:migrate-versions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate versions to new format';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        Movie::chunkById(100, function (Collection $movies) {
            $movies->each(function (Movie $movie) {
                $this->info("Migrating movie {$movie->id}");

                // Create a MovieVersion for each movie with the same data
                $version = new MovieVersion([
                    'format' => MovieFormat::DVD->value,
                    'release_date' => $movie->release_date,
                    'product_code' => $movie->product_code,
                ]);

                // Save the MovieVersion
                $movie->versions()->save($version);
            });
        });

        return Command::SUCCESS;
    }
}
