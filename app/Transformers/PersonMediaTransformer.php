<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Enums\MediaCollectionType;
use App\Models\Person;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class PersonMediaTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array<string>
     */
    protected array $defaultIncludes = [
        'profile',
    ];

    /**
     * A Fractal transformer.
     *
     * @return array<string, mixed>
     */
    public function transform(Person $person): array
    {
        return [
            'id' => $person->id,
        ];
    }

    /**
     * Include posters
     */
    public function includeProfile(Person $person): Collection
    {
        return $this->collection($person->getMedia(MediaCollectionType::Profile->value), new MediaTransformer());
    }
}
