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

class IVMovieSeeder extends Seeder
{
    public function run(): void
    {
        $movie_type = MovieType::where('name', 'IV')->first();

        $json = File::get('database/data/movies_iv.json');
        $movies = json_decode($json, true);

        foreach ($movies as $movie) {
            try {
                $movieRecord = Movie::create([
                    "title" => [
                        'jp' => $movie['title_jp'],
                        'en' => $movie['title_en'],
                    ],
                    'product_code' => $movie['product_code'],
                    "release_date" => $movie['release_date'],
                ]);

                $movieRecord->type()->associate($movie_type);

                if (array_key_exists('models', $movie) && sizeof($movie['models']) > 0) {
                    foreach ($movie['models'] as $model) {
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

                if (array_key_exists('genres', $movie) && sizeof($movie['genres']) > 0) {
                    foreach ($movie['genres'] as $genre) {
                        $movieRecord->attachTag($genre);
                    }
                }

                # If the movie has a studio, firstOrCreate it
                if (array_key_exists('studio', $movie) && $movie['studio'] != '') {
                    $studio = Studio::where('name->jp', $movie['studio'])->first();
                    if ($studio == null) {
                        $studio = Studio::create([
                            'name' => [
                                'jp' => $movie['studio'],
                            ]
                        ]);
                    }

                    $movieRecord->studio()->associate($studio);
                }

                Log::info("Adding poster for {$movie['product_code']}");
                $posterfile = sprintf("database/data/covers/%s.jpg", $movieRecord->product_code);
                if (File::exists($posterfile)) {
                    Log::info(sprintf("Found poster for %s", $movie['product_code']));
                    $movieRecord->addMedia($posterfile)->preservingOriginal()->toMediaCollection('poster');
                } else {
                    Log::info(sprintf("No poster found for %s", $movie['product_code']));
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
