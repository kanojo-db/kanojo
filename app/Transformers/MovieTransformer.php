<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Movie;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class MovieTransformer extends TransformerAbstract
{
    private string $language;

    private bool $showVersion;

    public function __construct(?string $language, bool $showVersion = false)
    {
        // If there is no language given, default to the user's preferred language
        if (! $language) {
            $language = app()->getLocale();
        }

        $this->language = $language;

        $this->showVersion = $showVersion;
    }

    /**
     * List of resources available to include
     *
     * @var array<string>
     */
    protected array $availableIncludes = [
        'cast',
        'studio',
    ];

    /**
     * A Fractal transformer.
     *
     * @return array<string, mixed>
     */
    public function transform(Movie $movie)
    {
        $values = [
            'backdrop_path' => $movie->fanart?->original_url,
            'id' => $movie->id,
            'original_title' => $movie->getTranslation('title', 'ja-JP', false),
            'poster_path' => $movie->poster?->original_url,
            'release_date' => $movie->release_date,
            'runtime' => $movie->length,
            'belongs_to_series' => $movie->series?->getTranslation('title', $this->language, false) ?
                $movie->series->getTranslation('title', $this->language, false) :
                $movie->series?->getTranslation('title', 'ja-JP', false),
            'title' => $movie->getTranslation('title', $this->language, false),
            'web_path' => route('movies.show', $movie->slug, true),
        ];

        if ($this->showVersion) {
            $values['product_code'] = $movie->versions->first()?->product_code;
        }

        return $values;
    }

    /**
     * Include Cast
     */
    public function includeCast(Movie $movie): Collection
    {
        $movie->load('models');

        return $this->collection($movie->models, new MovieCastTransformer($movie, $this->language));
    }

    /**
     * Include Studio
     */
    public function includeStudio(Movie $movie): ?Item
    {
        $movie->load('studio');

        return $movie->studio ? $this->item($movie->studio, new MovieStudioTransformer()) : null;
    }
}
