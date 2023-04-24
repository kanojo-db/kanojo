<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Movie;
use League\Fractal\TransformerAbstract;

class MovieSearchResultTransformer extends TransformerAbstract
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
    public function transform(Movie $movie)
    {
        return [
            'backdrop_path' => $movie->fanart?->original_url,
            'id' => $movie->id,
            'original_title' => $movie->getTranslation('title', 'ja-JP', false),
            'popularity' => $movie->popularity,
            'poster_path' => $movie->poster?->original_url,
            'release_date' => $movie->release_date,
            'title' => $movie->getTranslation('title', $this->language, false) ? $movie->getTranslation('title', $this->language, false) : null,
            'vote_average' => 0,
            'vote_count' => 0,
            'web_path' => route('movies.show', $movie->slug, true),
        ];
    }
}
