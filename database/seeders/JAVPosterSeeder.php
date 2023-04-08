<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\MediaCollectionType;
use App\Models\Movie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Throwable;

class JAVPosterSeeder extends Seeder
{
    public function run(): void
    {
        Log::info('JAVPosterSeeder started');

        // Get all files from the directory
        $files = scandir('database/data/covers/');

        foreach ($files as $path) {
            Log::info('JAVPosterSeeder processing file: '.$path);

            $movie = pathinfo($path, PATHINFO_FILENAME);
            $isFrontCover = str_contains($movie, '-front');

            if ($isFrontCover) {
                $movie = str_replace('-front', '', $movie);
            }

            $fullpath = 'database/data/covers/'.$path;

            try {
                $movieRecord = Movie::where('product_code', $movie)->first();

                if ($movieRecord === null) {
                    Log::info('Movie not found: '.$movie);

                    continue;
                }

                if (! $isFrontCover) {
                    // Check if the movie already has a full cover
                    $fullCover = $movieRecord->getFirstMedia(MediaCollectionType::FullCover->value);

                    if ($fullCover !== null) {
                        Log::info('Movie already has a full cover: '.$movie);

                        continue;
                    }

                    $movieRecord
                        ->addMedia($fullpath)
                        ->toMediaCollection(MediaCollectionType::FullCover->value);
                }
                if ($isFrontCover) {
                    // Check if the movie already has a front cover
                    $frontCover = $movieRecord->getFirstMedia(MediaCollectionType::FrontCover->value);

                    if ($frontCover !== null) {
                        Log::info('Movie already has a front cover: '.$movie);

                        continue;
                    }

                    $movieRecord
                        ->addMedia($fullpath)
                        ->toMediaCollection(MediaCollectionType::FrontCover->value);
                }
            } catch (Throwable $t) {
                Log::error($t->getMessage());
                Log::error($t->getTraceAsString());

                continue;
            }
        }
    }
}
