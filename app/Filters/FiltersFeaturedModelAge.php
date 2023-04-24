<?php

declare(strict_types=1);

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

/**
 * @implements Filter<\App\Models\Movie>
 */
class FiltersFeaturedModelAge implements Filter
{
    /**
     * @param  array<int, int>  $value
     */
    public function __invoke(Builder $query, $value, string $property): mixed
    {
        return $query->whereHas('models', function (Builder $query) use ($value) {
            $query->whereNotNull('birthdate')->whereNotNull('movie_person.age')->whereBetween('movie_person.age', $value);
        });
    }
}
