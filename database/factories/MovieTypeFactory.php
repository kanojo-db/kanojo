<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\MovieType;
use Illuminate\Database\Eloquent\Factories\Factory;

class MovieTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model|TModel>
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
