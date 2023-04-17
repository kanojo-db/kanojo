<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Rennokki\QueryCache\Traits\QueryCacheable;

class MovieType extends Model
{
    use HasFactory;
    use QueryCacheable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'types';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The default time to cache queries.
     *
     * @var int|\DateTime
     */
    public $cacheFor = 604800;

    /**
     * Get the movies of this type.
     *
     * @return HasMany<Movie>
     */
    public function movies(): HasMany
    {
        return $this->hasMany(Movie::class);
    }
}
