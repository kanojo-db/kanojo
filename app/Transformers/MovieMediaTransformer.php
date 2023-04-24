<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Enums\MediaCollectionType;
use App\Models\Movie;
use League\Fractal\TransformerAbstract;

class MovieMediaTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     */
    protected array $defaultIncludes = [
        'posters',
        'backdrops',
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Movie $movie)
    {
        return [
            'id' => $movie->id,
        ];
    }

    /**
     * Include posters
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includePosters(Movie $movie)
    {
        return $this->collection($movie->getMedia(MediaCollectionType::FrontCover->value), new MediaTransformer());
    }

    /**
     * Include backdrops
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeBackdrops(Movie $movie)
    {
        return $this->collection($movie->getMedia(MediaCollectionType::FullCover->value), new MediaTransformer());
    }
}
