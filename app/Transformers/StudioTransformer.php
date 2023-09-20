<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Studio;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class StudioTransformer extends TransformerAbstract
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
     *
     * @var array<string>
     */
    protected array $availableIncludes = [
        'movies',
    ];

    /**
     * A Fractal transformer.
     *
     * @return array<string, mixed>
     */
    public function transform(Studio $studio): array
    {
        return [
            'id' => $studio->id,
            'founded_date' => $studio->founded_date,
            'name' => $studio->getTranslation('name', $this->language, false),
            'original_name' => $studio->getTranslation('name', 'ja-JP', false),
            'logo_path' => $studio->logo?->original_url,
        ];
    }

    /**
     * Include Movies
     */
    public function includeMovies(Studio $studio): Collection
    {
        $paginator = $studio->movies()->latest('release_date')->paginate(25);

        return $this->collection($paginator->getCollection(), new MovieTransformer($this->language))->setPaginator(new IlluminatePaginatorAdapter($paginator));
    }
}
