<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersFeaturedModelAge implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        return $query->whereHas('models', function ($query) use ($value) {
            $query->whereBetween('movie_person.age', $value);
        });
    }
}
