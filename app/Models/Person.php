<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use JordanMiguel\LaravelPopular\Traits\Visitable;
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
    use Visitable;
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

    public function reports()
    {
        return $this->morphMany(ContentReport::class, 'reportable');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Movie>
     */
    public function movies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
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
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name_en' => $this->getTranslation('name', 'en'),
            'name_jp' => $this->getTranslation('name', 'jp'),
        ];
    }

    public function scopeBornBetween(Builder $query, string $start_date, string $end_date): Builder
    {
        return $query->where([
            [DB::raw('YEAR(birthdate)'), '>=', $start_date],
            [DB::raw('YEAR(birthdate)'), '<=', $end_date],
        ]);
    }
}
