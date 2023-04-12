<?php

declare(strict_types=1);

namespace App\Filters;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

/**
 * @template-implements Spatie\QueryBuilder\Filters\Filter<Movie>
 */
class FiltersFeaturedModelAge implements Filter
{
    public function __invoke(Builder $query, mixed $value, string $property): mixed
    {
        return $query->whereHas('models', function (Builder $query) use ($value) {
            $query->whereBetween('movie_person.age', $value);
        });
    }
}
