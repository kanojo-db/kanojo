<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\MovieType;
use App\Models\Person;
use App\Models\Series;
use App\Models\Studio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use JsonMachine\Items;
use Throwable;

class JAVLibraryMovieSeeder extends Seeder
{
    private function createMovie($movie): void
    {
        // Do some preliminary cleanup on a few values.

        $iv_movie_type = MovieType::find(2);
        $u18_movie_type = MovieType::find(3);
        $jav_movie_type = MovieType::find(1);
        $vr_movie_type = MovieType::find(4);

        $studio = null;
        // If the movie has a studio, firstOrCreate it
        if ($movie->studio != '' || $movie->series != '\n' || $movie->studio != null) {
            $movie->studio = str_replace('\n', '', $movie->studio);

            $studio = Studio::firstOrCreate([
                'name' => [
                    'ja-JP' => $movie->studio,
                ],
            ]);
        }

        $series = null;
        // If the movie has a series, firstOrCreate it
        if ($movie->series != '' || $movie->series != '\n' || $movie->series != null) {
            $movie->series = str_replace('\n', '', $movie->series);

            $series = Series::firstOrCreate([
                'name' => [
                    'ja-JP' => $movie->series,
                ],
            ]);
        }

        $movieRecord = Movie::create([
            'title' => [
                'ja-JP' => $movie->title_jp,
                'en-US' => $movie->title_en,
            ],
            'product_code' => $movie->product_code,
            'release_date' => $movie->release_date,
            'length' => intval($movie->runtime),
            'type_id' => in_array('VR', $movie->genres) ? $vr_movie_type->id : $jav_movie_type->id,
            'studio_id' => $studio?->id ?? null,
            'series_id' => $series?->id ?? null,
        ]);

        if (property_exists($movie, 'models') && count($movie->models) > 0) {
            foreach ($movie->models as $model) {
                $modelRecord = Person::firstOrCreate(
                    [
                        'name' => [
                            'ja-JP' => $model,
                        ],
                    ]);

                $movieRecord->models()->attach($modelRecord);
            }
        }

        if (property_exists($movie, 'genres') && count($movie->genres) > 0) {
            // Iterate over genres and attach them to the movie
            foreach ($movie->genres as $genre) {
                $movieRecord->attachTag($genre);
            }
        }

        $movieRecord->save();
    }

    public function run(): void
    {
        Log::info('JAVLibraryMovieSeeder started');

        $movies = Items::fromFile('scripts/movies_iv.json');

        foreach ($movies as $movie) {
            try {
                // JAVLibrary lists 3DSVR titles as DSVR, so we need to add a 3 to the beginning
                if (substr($movie->product_code, 0, 4) == 'DSVR') {
                    $movie->product_code = '3'.$movie->product_code;
                }

                $this->command->info('Processing movie: '.$movie->product_code);

                // If the movie already exists and the title is identical, skip it
                $existingMovie = Movie::where('product_code', '=', $movie->product_code)->first();

                if ($existingMovie != null) {
                    // If the movie exists, but the title is different, it's a different movie
                    if ($existingMovie->getTranslation('title', 'ja-JP', false) != $movie->title_jp) {
                        $this->command->info('Movie already exists, but title is different: '.$movie->product_code);

                        // Check if a movie with the same name exists
                        $existingMovie = Movie::where('title->ja-JP', '=', $movie->title_jp)->first();

                        // If it does, skip it
                        if ($existingMovie == null) {
                            $this->command->info('Movie does not exist, creating: '.$movie->product_code);

                            // $this->createMovie($movie);

                            continue;
                        }

                    }

                    $studio = null;
                    // If the movie exists, we still need to update the series and studio.
                    if (($movie->studio != '' || $movie->series != '\n' || $movie->studio != null) && $existingMovie->studio == null) {
                        $this->command->info('Updating studio');

                        $studio = Studio::where('name->ja-JP', $movie->studio)->first();

                        if ($studio == null) {
                            $this->command->info('Studio does not exist, creating: '.$movie->studio);

                            $studio = Studio::create([
                                'name' => [
                                    'ja-JP' => $movie->studio,
                                ],
                            ]);
                        }

                        $existingMovie->studio()->associate($studio);
                        $existingMovie->save();
                    }

                    if (($movie->series != '' || $movie->series != '\n' || $movie->series != null) && $existingMovie->series == null) {
                        $this->command->info('Updating series');
                        $series = Series::where('title->ja-JP', $movie->series)->first();

                        if ($series == null) {
                            $this->command->info('Series does not exist, creating: '.$movie->series);

                            $series = Series::create([
                                'title' => [
                                    'ja-JP' => $movie->series,
                                ],
                            ]);
                        }

                        if ($movie->studio != null) {
                            if ($studio == null) {
                                $studio = Studio::where('name->ja-JP', $movie->studio)->first();
                            }

                            $series->studio()->associate($studio);

                            $series->save();
                        }

                        $existingMovie->series()->associate($series);
                        $existingMovie->save();

                    }

                    // Update the length of the movie
                    $existingMovie->update([
                        'length' => intval($movie->runtime),
                    ]);

                    continue;
                }

                Log::info('JAVLibraryMovieSeeder processing movie: '.$movie->product_code);
                $this->command->info('Adding movie: '.$movie->product_code);

                // $this->createMovie($movie);
            } catch (Throwable $t) {
                Log::error($t->getMessage());
                Log::error($t->getTraceAsString());

                continue;
            }
        }

    }
}
