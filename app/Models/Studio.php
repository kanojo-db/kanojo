<?php

declare(strict_types=1);

namespace App\Models;

use App\DataTransferObjects\MovieData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Spatie\LaravelData\DataCollection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Studio extends Model implements AuditableContract, HasMedia
{
    use Auditable;
    use HasFactory;
    use Searchable;
    use InteractsWithMedia;
    use HasTranslations;
    use SoftDeletes;
    use QueryCacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'founded_date',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var string[]
     */
    public $translatable = ['name'];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'movies' => DataCollection::class.':'.MovieData::class,
    ];

    /**
     * Get the movies for the studio.
     *
     * @return HasMany<Movie>
     */
    public function movies(): HasMany
    {
        return $this->hasMany(Movie::class);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        $array = $this->toArray();

        // TODO: Eventually refine this.

        return $array;
    }
}
