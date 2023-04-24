<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Series;
use League\Fractal\TransformerAbstract;

class SeriesSearchResultTransformer extends TransformerAbstract
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
    public function transform(Series $series)
    {
        return [
            'id' => $series->id,
            'title' => $series->getTranslation('title', $this->language, false),
            'original_title' => $series->getTranslation('title', 'ja-JP', false),
            'poster_path' => null, // $series->poster?->original_url,
        ];
    }
}
