<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Enums\MediaCollectionType;
use App\Models\Studio;
use League\Fractal\TransformerAbstract;

class StudioMediaTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     */
    protected array $defaultIncludes = [
        'logo',
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Studio $studio)
    {
        return [
            'id' => $studio->id,
        ];
    }

    /**
     * Include posters
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeLogo(Studio $studio)
    {
        return $this->collection($studio->getMedia(MediaCollectionType::Logo->value), new MediaTransformer());
    }
}
