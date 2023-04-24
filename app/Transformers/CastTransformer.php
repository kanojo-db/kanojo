<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Person;
use League\Fractal\TransformerAbstract;

class CastTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Person $person)
    {
        return [
            'gender' => $person->gender?->id,
            'id' => $person->id,
            'name' => $person->getTranslation('name', 'en-US', false),
            'original_name' => $person->getTranslation('name', 'ja-JP', false),
            'popularity' => $person->popularity,
            'profile_path' => $person->poster?->original_url,
            'web_path' => route('models.show', $person->slug, true),
        ];
    }
}
