<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Movie;
use League\Fractal\TransformerAbstract;

class MovieCreditsTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array<string>
     */
    protected array $defaultIncludes = [
        'cast',
    ];

    /**
     * A Fractal transformer.
     *
     * @return array<string, mixed>
     */
    public function transform(Movie $movie)
    {
        return [
            'id' => $movie->id,
        ];
    }

    /**
     * Include cast
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeCast(Movie $movie)
    {
        return $this->collection($movie->models, new CastTransformer());
    }
}
