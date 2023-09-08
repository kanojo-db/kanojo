<?php

declare(strict_types=1);

namespace App\Models;

use App\Events\MovieVersionUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovieVersion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'format',
        'release_date',
        'product_code',
        'movie_id',
        'barcode',
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, string>
     */
    protected $dispatchesEvents = [
        'updated' => MovieVersionUpdated::class,
    ];

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    /**
     * The movie this version belongs to.
     *
     * @return BelongsTo<Movie, MovieVersion>
     */
    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }
}
