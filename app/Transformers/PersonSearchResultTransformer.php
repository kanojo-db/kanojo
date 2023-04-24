<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Person;
use League\Fractal\TransformerAbstract;

class PersonSearchResultTransformer extends TransformerAbstract
{
    private string $language;

    public function __construct(?string $language)
    {
        // If there is no language given, default to the user's preferred language
        if (! $language) {
            $language = app()->getLocale();
        }

        $this->language = $language;
    }

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
            'name' => $person->getTranslation('name', $this->language, false),
            'original_name' => $person->getTranslation('name', 'ja-JP', false),
            'popularity' => $person->popularity,
            'profile_path' => $person->poster?->original_url,
        ];
    }
}
