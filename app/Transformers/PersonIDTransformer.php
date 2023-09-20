<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Person;
use League\Fractal\TransformerAbstract;

class PersonIDTransformer extends TransformerAbstract
{
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
}
