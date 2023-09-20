<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Enums\MediaCollectionType;
use App\Models\Movie;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class MovieMediaTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array<string>
     */
    protected array $defaultIncludes = [
        'posters',
        'backdrops',
    ];

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

    /**
     * Include posters
     */
    public function includePosters(Movie $movie): Collection
    {
        return $this->collection($movie->getMedia(MediaCollectionType::FrontCover->value), new MediaTransformer());
    }

    /**
     * Include backdrops
     */
    public function includeBackdrops(Movie $movie): Collection
    {
        return $this->collection($movie->getMedia(MediaCollectionType::FullCover->value), new MediaTransformer());
    }
}
