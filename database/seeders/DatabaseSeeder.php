<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PersonSeeder::class);
        $this->call(MovieTypeSeeder::class);
        $this->call(U18MovieSeeder::class);
        $this->call(IVMovieSeeder::class);
        $this->call(JAVMovieSeeder::class);
    }
}
