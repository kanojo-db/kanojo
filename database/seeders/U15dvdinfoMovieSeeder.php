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

class U15dvdinfoMovieSeeder extends Seeder
{
    public function run(): void
    {
        Log::info('U15dvdinfoMovieSeeder started');

        $u18_movie_type = MovieType::where('name', 'U-18')->first();

        $movies = Items::fromFile('database/data/u15dvdinfo.json');

        foreach ($movies as $movie) {
            Log::info('U15dvdinfoMovieSeeder processing movie: '.$movie->product_code);

            try {
                // If the movie already exists, skip it
                if (Movie::where('product_code', '=', $movie->product_code)->first() != null) {
                    Log::info('U15dvdinfoMovieSeeder skipping movie: '.$movie->product_code);

                    continue;
                }

                $movieRecord = Movie::create([
                    'title' => [
                        'ja-JP' => $movie->title_jp,
                        'en-US' => $movie->title_en,
                    ],
                    'product_code' => $movie->product_code,
                    'release_date' => $movie->release_date,
                ]);

                $movieRecord->type()->associate($u18_movie_type);

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
