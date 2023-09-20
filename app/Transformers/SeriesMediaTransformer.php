<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Enums\MediaCollectionType;
use App\Models\Series;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class SeriesMediaTransformer extends TransformerAbstract
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
    public function transform(Series $series): array
    {
        return [
            'id' => $series->id,
        ];
    }

    /**
     * Include posters
     */
    public function includePosters(Series $series): Collection
    {
        return $this->collection($series->getMedia(MediaCollectionType::FrontCover->value), new MediaTransformer());
    }

    /**
     * Include backdrops
     */
    public function includeBackdrops(Series $series): Collection
    {
        return $this->collection($series->getMedia(MediaCollectionType::FullCover->value), new MediaTransformer());
    }
}
