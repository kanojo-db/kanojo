<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Movie;
use League\Fractal\TransformerAbstract;

class MovieTransformer extends TransformerAbstract
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
     * List of resources available to include
     */
    protected array $availableIncludes = [
        'cast',
        'studio',
    ];

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
            'poster_path' => $movie->poster?->original_url,
            'release_date' => $movie->release_date,
            'runtime' => $movie->length,
            'title' => $movie->getTranslation('title', $this->language, false),
            'web_path' => route('movies.show', $movie->slug, true),
        ];
    }

    /**
     * Include Cast
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeCast(Movie $movie)
    {
        $movie->load('models');

        return $this->collection($movie->models, new MovieCastTransformer($movie, $this->language));
    }

    /**
     * Include Studio
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeStudio(Movie $movie)
    {
        $movie->load('studio');

        return $movie->studio ? $this->item($movie->studio, new MovieStudioTransformer()) : null;
    }
}