<?php

namespace Database\Seeders;

use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get('database/data/people.json');
        $people = json_decode($json, true);

        foreach ($people as $person) {
            Person::create([
                "name" => [
                    'jp' => $person['name_jp'],
                    "en" => $person['name_en'],
                ],
                "birthdate" => $person['birthdate'],
                "created_at" => now()->toDateString(),
                "updated_at" => now()->toDateString(),
            ]);
        }
    }
}
