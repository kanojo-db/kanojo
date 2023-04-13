<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Person extends Model implements AuditableContract, HasMedia
{
    use Auditable;
    use HasFactory;
    use Searchable;
    use InteractsWithMedia;
    use HasTranslations;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'birthdate',
        'career_start',
        'career_end',
        'height',
        'bust',
        'waist',
        'hip',
        'blood_type',
        'cup_size',
        'breast_implants',
        'country',
    ];

    /**
     * Content reports concerning this person.
     *
     * @return MorphMany<ContentReport>
     */
    public function reports(): MorphMany
    {
        return $this->morphMany(ContentReport::class, 'reportable');
    }

    /**
     * Movies this person has featured in.
     *
     * @return BelongsToMany<Movie>
     */
    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class)->withPivot('age')->withTimestamps();
    }

    /**
     * The attributes that are translatable.
     *
     * @var string[]
     */
    public $translatable = ['name'];

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

    /**
     * Scope to filter persons born between two dates.
     *
     * @param  Builder<Person>  $query
     * @return Builder<Person>
     */
    public function scopeBornBetween(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->where([
            [DB::raw('YEAR(birthdate)'), '>=', $startDate],
            [DB::raw('YEAR(birthdate)'), '<=', $endDate],
        ]);
    }
}
