<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\MovieVersion;
use League\Fractal\TransformerAbstract;

class MovieVersionSearchResultTransformer extends TransformerAbstract
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
    public function transform(MovieVersion $movieVersion)
    {
        return [
            'backdrop_path' => $movieVersion->movie?->fanart?->original_url,
            'id' => $movieVersion->movie?->id,
            'original_title' => $movieVersion->movie?->getTranslation('title', 'ja-JP', false),
            'popularity' => $movieVersion->movie?->popularity,
            'poster_path' => $movieVersion->movie?->poster?->original_url,
            'product_code' => $movieVersion->product_code,
            'release_date' => $movieVersion->release_date,
            'title' => $movieVersion->movie?->getTranslation('title', $this->language, false) ?
                $movieVersion->movie->getTranslation('title', $this->language, false) :
                null,
            'vote_average' => 0,
            'vote_count' => 0,
            'web_path' => route('movies.show', $movieVersion->movie?->slug, true),
        ];
    }
}
