<?php

declare(strict_types=1);

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

/**
 * @implements Filter<\App\Models\Movie>
 */
class FiltersMovieMedia implements Filter
{
    /**
     * @param  bool  $value
     */
    public function __invoke(Builder $query, mixed $value, string $property): mixed
    {
        if ($value === false) {
            return $query->whereHas('media');
        }

        return $query->whereDoesntHave('media');
    }
}
