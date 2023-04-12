<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Studio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Studio>
 */
class StudioFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var mixed
     */
    protected $model = Studio::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->text(64),
            'founded_date' => $this->faker->date(),
        ];
    }
}
