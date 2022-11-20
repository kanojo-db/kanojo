<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PersonSeeder::class);
        $this->call(MovieTypeSeeder::class);
        $this->call(U18MovieSeeder::class);
        $this->call(IVMovieSeeder::class);
        $this->call(JAVMovieSeeder::class);
    }
}
