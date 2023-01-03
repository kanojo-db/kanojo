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

class JAVLibraryMovieSeeder extends Seeder
{
    public function run(): void
    {
        Log::info('JAVLibraryMovieSeeder started');

        $jav_movie_type = MovieType::where('name', 'JAV')->first();
        $vr_movie_type = MovieType::where('name', 'VR')->first();

        $movies = Items::fromFile('database/data/javlib.json');

        foreach ($movies as $movie) {
            try {
                # If the movie already exists, skip it
                if (Movie::where('product_code', '=', $movie->product_code)->first() != null) {
                    Log::info('JAVLibraryMovieSeeder skipping movie: ' . $movie->product_code);
                    continue;
                }

                Log::info('JAVLibraryMovieSeeder processing movie: ' . $movie->product_code);

                $movieRecord = Movie::create([
                    "title" => [
                        'jp' => $movie->title_jp,
                        'en' => $movie->title_en,
                    ],
                    'product_code' => $movie->product_code,
                    "release_date" => $movie->release_date,
                ]);

                # If "VR" is in the genres, then it's a VR movie
                if (in_array("VR", $movie->genres)) {
                    $movieRecord->type()->associate($vr_movie_type);

                    # Remove "VR" from the genres
                    $movie->genres = array_diff($movie->genres, ['VR']);
                } else {
                    $movieRecord->type()->associate($jav_movie_type);
                }

                if (property_exists($movie, 'models') && sizeof($movie->models) > 0) {
                    foreach ($movie->models as $model) {
                        # If the model already exists, get it, otherwise create it
                        $modelRecord = Person::where('name->jp', $model)->first();
                        if ($modelRecord == null) {
                            $modelRecord = Person::create([
                                'name' => [
                                    'jp' => $model,
                                ]
                            ]);
                        }

                        $movieRecord->models()->attach($modelRecord);
                    }
                }

                # If the movie has a studio, firstOrCreate it
                if ($movie->studio != '') {
                    $studio = Studio::where('name->jp', $movie->studio)->first();
                    if ($studio == null) {
                        $studio = Studio::create([
                            'name' => [
                                'jp' => $movie->studio,
                            ]
                        ]);
                    }

                    $movieRecord->studio()->associate($studio);
                }

                if (property_exists($movie, 'genres') && sizeof($movie->genres) > 0) {
                    # Iterate over genres and attach them to the movie
                    foreach ($movie->genres as $genre) {
                        $movieRecord->attachTag($genre);
                    }
                }

                Log::info("Adding poster for {$movie->product_code}");
                $posterfile = sprintf("database/data/covers/%s.jpg", $movieRecord->product_code);
                if (File::exists($posterfile)) {
                    Log::info(sprintf("Found poster for %s", $movie->product_code));
                    $movieRecord->addMedia($posterfile)->preservingOriginal()->toMediaCollection('poster');
                } else {
                    Log::info(sprintf("No poster found for %s", $movie->product_code));
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
