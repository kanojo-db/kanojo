<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\MovieType;
use App\Models\Person;
use App\Models\Studio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use JsonMachine\Items;
use Throwable;

class JAVMovieSeeder extends Seeder
{
    public function run(): void
    {
        Log::info('JAVMovieSeeder started');

        $jav_movie_type = MovieType::where('name', 'JAV')->first();
        $vr_movie_type = MovieType::where('name', 'VR')->first();

        $movies = Items::fromFile('database/data/movies_jav.json');

        foreach ($movies as $movie) {
            Log::info('JAVMovieSeeder processing movie: '.$movie->product_code);

            try {
                $movieRecord = Movie::create([
                    'title' => [
                        'jp' => $movie->title_jp,
                        'en' => $movie->title_en,
                    ],
                    'product_code' => $movie->product_code,
                    'release_date' => $movie->release_date,
                ]);

                // If "VR" is in the genres, then it's a VR movie
                if (in_array('VR', $movie->genres)) {
                    $movieRecord->type()->associate($vr_movie_type);

                    // Remove "VR" from the genres
                    $movie->genres = array_diff($movie->genres, ['VR']);
                } else {
                    $movieRecord->type()->associate($jav_movie_type);
                }

                if (property_exists($movie, 'models') && count($movie->models) > 0) {
                    foreach ($movie->models as $model) {
                        $modelRecord = Person::where('name->jp', $model)->first();
                        if ($modelRecord == null) {
                            $modelRecord = Person::create([
                                'name' => [
                                    'jp' => $model,
                                ],
                            ]);
                        }

                        $movieRecord->models()->attach($modelRecord);
                    }
                }

                // If the movie has a studio, firstOrCreate it
                if ($movie->studio != '') {
                    $studio = Studio::where('name->jp', $movie->studio)->first();
                    if ($studio == null) {
                        $studio = Studio::create([
                            'name' => [
                                'jp' => $movie->studio,
                            ],
                        ]);
                    }

                    $movieRecord->studio()->associate($studio);
                }

                if (property_exists($movie, 'genres') && count($movie->genres) > 0) {
                    // Iterate over genres and attach them to the movie
                    foreach ($movie->genres as $genre) {
                        $movieRecord->attachTag($genre);
                    }
                }

                Log::info("Adding poster for {$movie->product_code}");
                $posterfile = sprintf('database/data/covers/%s.jpg', $movieRecord->product_code);
                if (File::exists($posterfile)) {
                    Log::info(sprintf('Found poster for %s', $movie->product_code));
                    $movieRecord->addMedia($posterfile)->preservingOriginal()->toMediaCollection('poster');
                } else {
                    Log::info(sprintf('No poster found for %s', $movie->product_code));
                }

                $movieRecord->save();
            } catch (Throwable $t) {
                Log::error($t->getMessage());
                Log::error($t->getTraceAsString());

                continue;
            }
        }

    }
}
