<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Person;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class PersonTransformer extends TransformerAbstract
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
    public function transform(Person $person): array
    {
        return [
            'birthday' => $person->birthdate,
            'career_end' => $person->career_end,
            'career_start' => $person->career_start,
            'country' => $person->country,
            'gender' => $person->gender?->id,
            'id' => $person->id,
            'name' => $person->getTranslation('name', $this->language, false),
            'original_name' => $person->getTranslation('name', 'ja-JP', false),
            'popularity' => $person->popularity,
        ];
    }

    /**
     * Include Movies
     */
    public function includeMovies(Person $person): Collection
    {
        $paginator = $person->movies()->latest('release_date')->paginate(25);

        return $this->collection($paginator->getCollection(), new MovieTransformer($this->language))->setPaginator(new IlluminatePaginatorAdapter($paginator));
    }
}
