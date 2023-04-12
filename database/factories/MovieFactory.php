<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var mixed
     */
    protected $model = Movie::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->text(64),
            'product_code' => $this->faker->text(6),
            'release_date' => $this->faker->date(),
            'length' => $this->faker->numberBetween(30, 720),
        ];
    }
}
