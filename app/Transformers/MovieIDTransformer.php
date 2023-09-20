<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Movie;
use League\Fractal\TransformerAbstract;

class MovieIDTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array<string, mixed>
     */
    public function transform(Movie $movie): array
    {
        return [
            'id' => $movie->id,
        ];
    }
}
