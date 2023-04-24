<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Enums\MediaCollectionType;
use App\Models\Person;
use League\Fractal\TransformerAbstract;

class PersonMediaTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     */
    protected array $defaultIncludes = [
        'profile',
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Person $person)
    {
        return [
            'id' => $person->id,
        ];
    }

    /**
     * Include posters
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeProfile(Person $person)
    {
        return $this->collection($person->getMedia(MediaCollectionType::Profile->value), new MediaTransformer());
    }
}
