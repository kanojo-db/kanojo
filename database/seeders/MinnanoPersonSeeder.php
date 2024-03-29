<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Person;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use JsonMachine\Items;

class MinnanoPersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Log::info('MinnanoPersonSeeder started');

        $people = Items::fromFile('database/data/minnanoav.json');

        foreach ($people as $person) {
            $person = Person::updateOrCreate([
                'name->jp' => $person->name_jp,
            ], [
                'name' => [
                    'ja-JP' => $person->name_jp,
                    'en-US' => $person->name_en,
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
