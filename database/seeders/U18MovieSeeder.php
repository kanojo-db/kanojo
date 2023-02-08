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
use Throwable;

class U18MovieSeeder extends Seeder
{
    public function run(): void
    {
        $movie_type = MovieType::where('name', 'U-18')->first();

        $json = File::get('database/data/movies_u18.json');
        $movies = json_decode($json, true);

        foreach ($movies as $movie) {
            try {
                $movieRecord = Movie::create([
                    'title' => [
                        'ja-JP' => $movie['title_jp'],
                        'en-US' => $movie['title_en'],
                    ],
                    'product_code' => $movie['product_code'],
                    'release_date' => $movie['release_date'],
                ]);

                $movieRecord->type()->associate($movie_type);

                if (array_key_exists('models', $movie) && count($movie['models']) > 0) {
                    foreach ($movie['models'] as $model) {
                        $modelRecord = Person::where('name->jp', $model)->first();
                        if ($modelRecord == null) {
                            $modelRecord = Person::create([
                                'name' => [
                                    'ja-JP' => $model,
                                ],
                            ]);
                        }

                        $movieRecord->models()->attach($modelRecord);
                    }
                }

                if (array_key_exists('genres', $movie) && count($movie['genres']) > 0) {
                    // Iterate over genres and attach them to the movie
                    foreach ($movie['genres'] as $genre) {
                        $movieRecord->attachTag($genre);
                    }
                }

                // If the movie has a studio, firstOrCreate it
                if (array_key_exists('studio', $movie) && $movie['studio'] != '') {
                    $studio = Studio::where('name->jp', $movie['studio'])->first();
                    if ($studio == null) {
                        $studio = Studio::create([
                            'name' => [
                                'ja-JP' => $movie['studio'],
                            ],
                        ]);
                    }

                    $movieRecord->studio()->associate($studio);
                }

                Log::info("Adding poster for {$movie['product_code']}");
                $posterfile = sprintf('database/data/covers/%s.jpg', $movie['product_code']);
                if (File::exists($posterfile)) {
                    Log::info(sprintf('Found poster for %s', $movie['product_code']));
                    $movieRecord->addMedia($posterfile)->preservingOriginal()->toMediaCollection('poster');
                } else {
                    Log::info(sprintf('No poster found for %s', $movie['product_code']));
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
