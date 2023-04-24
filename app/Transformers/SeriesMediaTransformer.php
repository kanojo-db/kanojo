<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Enums\MediaCollectionType;
use App\Models\Series;
use League\Fractal\TransformerAbstract;

class SeriesMediaTransformer extends TransformerAbstract
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
    public function transform(Series $series)
    {
        return [
            'id' => $series->id,
        ];
    }

    /**
     * Include posters
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includePosters(Series $series)
    {
        return $this->collection($series->getMedia(MediaCollectionType::FrontCover->value), new MediaTransformer());
    }

    /**
     * Include backdrops
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeBackdrops(Series $series)
    {
        return $this->collection($series->getMedia(MediaCollectionType::FullCover->value), new MediaTransformer());
    }
}
