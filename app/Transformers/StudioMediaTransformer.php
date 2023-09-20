<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Enums\MediaCollectionType;
use App\Models\Studio;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class StudioMediaTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array<string>
     */
    protected array $defaultIncludes = [
        'logo',
    ];

    /**
     * A Fractal transformer.
     *
     * @return array<string, mixed>
     */
    public function transform(Studio $studio): array
    {
        return [
            'id' => $studio->id,
        ];
    }

    /**
     * Include posters
     */
    public function includeLogo(Studio $studio): Collection
    {
        return $this->collection($studio->getMedia(MediaCollectionType::Logo->value), new MediaTransformer());
    }
}
