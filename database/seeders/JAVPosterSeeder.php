<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\MediaCollectionType;
use App\Models\Movie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Spatie\Image\Image;
use Throwable;

class JAVPosterSeeder extends Seeder
{
    public function run(): void
    {
        Log::info('JAVPosterSeeder started');

        // Get all files from the directory
        $files = scandir('database/data/covers/');

        foreach ($files as $path) {
            // Skip the . and .. directories
            if ($path === '.' || $path === '..') {
                continue;
            }

            Log::info('JAVPosterSeeder processing file: '.$path);

            $movie = pathinfo($path, PATHINFO_FILENAME);
            $isFrontCover = str_contains($movie, '-front');

            if ($isFrontCover) {
                $movie = str_replace('-front', '', $movie);
            }

            // If the movie starts with DSVR, prepend it with 3
            if (str_starts_with($movie, 'DSVR')) {
                $movie = '3'.$movie;
            }

            $fullpath = 'database/data/covers/'.$path;

            try {
                $movieCount = Movie::where('product_code', $movie)->count();

                // If the count is more than 1, log it and skip it. It will be handled manually
                if ($movieCount > 1) {
                    Log::info('Movie count is more than 1: '.$movie);
                    $this->command->info('Movie count is more than 1: '.$movie);

                    continue;
                }

                $movieRecord = Movie::where('product_code', $movie)->first();

                if ($movieRecord === null) {
                    Log::info('Movie not found: '.$movie);
                    $this->command->info('Movie not found: '.$movie);

                    continue;
                }

                if (! $isFrontCover) {
                    // Check if the movie already has a full cover
                    $fullCover = $movieRecord->getFirstMedia(MediaCollectionType::FullCover->value);

                    if ($fullCover !== null) {
                        Log::info('Movie already has a full cover: '.$movie);
                        $this->command->info('Movie already has a full cover: '.$movie);

                        continue;
                    }

                    $this->command->info('Adding full cover to movie: '.$movie);

                    $image = Image::load($fullpath);

                    $movieRecord
                        ->addMedia($fullpath)
                        ->withCustomProperties([
                            'user' => 1,
                            'width' => $image->getWidth(),
                            'height' => $image->getHeight(),
                        ])
                        ->toMediaCollection(MediaCollectionType::FullCover->value);
                }

                if ($isFrontCover) {
                    // Check if the movie already has a front cover
                    $frontCover = $movieRecord->getFirstMedia(MediaCollectionType::FrontCover->value);

                    if ($frontCover !== null) {
                        Log::info('Movie already has a front cover: '.$movie);
                        $this->command->info('Movie already has a front cover: '.$movie);

                        continue;
                    }

                    $this->command->info('Adding front cover to movie: '.$movie);

                    $image = Image::load($fullpath);

                    $movieRecord
                        ->addMedia($fullpath)
                        ->withCustomProperties([
                            'user' => 1,
                            'width' => $image->getWidth(),
                            'height' => $image->getHeight(),
                        ])
                        ->toMediaCollection(MediaCollectionType::FrontCover->value);
                }
            } catch (Throwable $t) {
                $this->command->error('Error adding cover to movie: '.$movie);

                Log::error($t->getMessage());
                Log::error($t->getTraceAsString());

                continue;
            }
        }
    }
}
