<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Person;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get('database/data/people.json');
        $people = json_decode($json, true);

        foreach ($people as $person) {
            Person::create([
                'name' => [
                    'ja-JP' => $person['name_jp'],
                    'en-US' => $person['name_en'],
                ],
                'birthdate' => $person['birthdate'],
                'created_at' => now()->toDateString(),
                'updated_at' => now()->toDateString(),
            ]);
        }
    }
}
