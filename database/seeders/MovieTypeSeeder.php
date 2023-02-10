<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\MovieType;
use Illuminate\Database\Seeder;

class MovieTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * There's only really three types we want/need to support here:
         * - JAV (Japanese Adult Video)
         * - IV (Idol Video, for all the Gravure stuff)
         * - U-18 (Under Eighteen, for all the underage Gravure stuff)
         */
        $json = file_get_contents('database/data/movie_types.json');
        $movie_types = json_decode($json, true);

        foreach ($movie_types as $movie_type) {
            MovieType::create([
                'name' => $movie_type['name'],
            ]);
        }
    }
}
