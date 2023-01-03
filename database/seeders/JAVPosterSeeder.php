<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\MovieType;
use App\Models\Person;
use App\Models\Studio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Throwable;
use \JsonMachine\Items;

class JAVPosterSeeder extends Seeder
{
    public function run(): void
    {
        Log::info('JAVPosterSeeder started');

        # Get contents from ajvr.txt to an array, each line is a product code
        $movies = explode("\n", file_get_contents('database/data/ajvr.txt'));

        foreach ($movies as $movie) {
            Log::info('JAVPosterSeeder processing movie: ' . $movie);

            try {
                $movieRecord = Movie::where('product_code', $movie)->first();

                if ($movieRecord == null) {
                    Log::info("Movie {$movie} not found");
                    continue;
                }

                Log::info("Adding poster for {$movie}");
                $posterfile = sprintf("database/data/vr_covers/%s.jpg", $movie);
                if (File::exists($posterfile)) {
                    Log::info(sprintf("Found poster for %s", $movie));
                    $movieRecord->addMedia($posterfile)->preservingOriginal()->toMediaCollection('poster');
                } else {
                    Log::info(sprintf("No poster found for %s", $movie));
                }

                $movieRecord->save();
            } catch (Throwable $t) {
                Log::error($t->getMessage());
                Log::error($t->getTraceAsString());
                continue;
            }
        }

        return;
    }
}
