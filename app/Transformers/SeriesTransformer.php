<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Series;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\TransformerAbstract;

class SeriesTransformer extends TransformerAbstract
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
        'movies',
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Series $series)
    {
        return [
            'id' => $series->id,
            'poster_path' => null,
            'backdrop_path' => null,
            'title' => $series->getTranslation('title', $this->language, false),
            'original_title' => $series->getTranslation('title', 'ja-JP', false),
        ];
    }

    /**
     * Include Movies
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeMovies(Series $series)
    {
        $paginator = $series->movies()->latest('release_date')->paginate(25);

        return $this->collection($paginator->getCollection(), new MovieTransformer($this->language))->setPaginator(new IlluminatePaginatorAdapter($paginator));
    }
}
