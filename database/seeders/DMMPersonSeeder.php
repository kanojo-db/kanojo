<?php

namespace Database\Seeders;

use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use JsonMachine\Items;

class DMMPersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $people = Items::fromFile('database/data/dmm_actress.json');

        foreach ($people as $person) {
            $existing_person = Person::where('name->jp', $person->name_jp)->first();

            // If we found a person
            if ($existing_person != null) {
                // Update the person
                $existing_person->birthdate = $person->birthdate;
                $existing_person->blood_type = $person->blood_type;
                $existing_person->height = $person->height;
                $existing_person->bust = $person->bust;
                $existing_person->waist = $person->waist;
                $existing_person->hip = $person->hip;
                $existing_person->cup_size = $person->cup_size;
                $existing_person->country = $person->country;

                $existing_person->save();
            } else {
                // Create a new person
                Person::create([
                    'name' => [
                        'jp' => $person->name_jp,
                    ],
                    'birthdate' => $person->birthdate,
                    'blood_type' => $person->blood_type,
                    'height' => $person->height,
                    'bust' => $person->bust,
                    'waist' => $person->waist,
                    'hip' => $person->hip,
                    'cup_size' => $person->cup_size,
                    'country' => $person->country,
                ]);
            }
        }
    }
}
