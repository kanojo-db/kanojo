<?php

declare(strict_types=1);

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersFeaturedModelAge implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        return $query->whereHas('models', function (Builder $query) use ($value) {
            $query->whereBetween('movie_person.age', $value);
        });
    }
}
