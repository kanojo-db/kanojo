<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Studio;
use League\Fractal\TransformerAbstract;

class StudioSearchResultTransformer extends TransformerAbstract
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
     * @return array<string, mixed>
     */
    public function transform(Studio $studio): array
    {
        return [
            'id' => $studio->id,
            'logo_path' => $studio->logo?->original_url,
            'name' => $studio->getTranslation('name', $this->language, false),
            'original_name' => $studio->getTranslation('name', 'ja-JP', false),
            'founded_date' => $studio->founded_date,
        ];
    }
}
