<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\MediaCollectionType;
use App\Models\Person;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class GfriendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dir = 'y-Attractive';

        $files = scandir("database/data/gfriends/Content/{$dir}/");

        foreach ($files as $file) {
            try {
                Log::info("Processing {$file}");

                $modelName = basename($file, '.jpg');

                Log::info("Model name: {$modelName}");

                $person = Person::where('name->ja-JP', $modelName)->first();

                if ($person) {
                    Log::info("Adding {$modelName} profile image (ID: {$person->id})");

                    $person
                        ->addMedia("database/data/gfriends/Content/{$dir}/".$file)
                        ->preservingOriginal()
                        ->toMediaCollection(MediaCollectionType::Profile->value);
                }
            } catch (\Throwable $th) {
                Log::error("Exception: {$th->getMessage()}");
                Log::error($th->getTraceAsString());

                continue;
            }
        }
    }
}
