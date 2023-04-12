<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\MovieType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\MovieType>
 */
class MovieTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var mixed
     */
    protected $model = MovieType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->text(6),
        ];
    }
}
