<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;
use Spatie\Translatable\HasTranslations;
use Cog\Contracts\Love\Reactable\Models\Reactable as ReactableInterface;
use Cog\Laravel\Love\Reactable\Models\Traits\Reactable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use \JordanMiguel\LaravelPopular\Traits\Visitable;

class Movie extends Model implements HasMedia, AuditableContract, ReactableInterface
{
    use HasFactory;
    use HasTags;
    use HasTranslations;
    use InteractsWithMedia;
    use Searchable;
    use Auditable;
    use Reactable;
    use Visitable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'original_title',
        'product_code',
        'release_date',
        'length',
        'studio_id',
        'type_id',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var string[]
     */
    public $translatable = ['title'];

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<Studio>
     */
    public function studio(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<MovieType>
     */
    public function type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MovieType::class);
    }

    /**
     * Define the media collection for movies
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('poster')->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Person>
     */
    public function models(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Person::class);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = [
            'id' => $this->id,
            'title_en' => $this->getTranslation('title', 'en'),
            'title_jp' => $this->getTranslation('title', 'jp'),
            'product_code' => $this->product_code,
        ];

        return $array;
    }

    /**
     * Modify the query used to retrieve models when making all of the models searchable.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function makeAllSearchableUsing($query)
    {
        return $query->with(['tags', 'studio', 'models']);
    }

    public function scopeFilterHidden(Builder $query): Builder
    {
        if (Auth::check()) {
            $user = Auth::user();

            $shouldShowJav = Auth::user()->settings->get('show_jav');
            $shouldShowVr = Auth::user()->settings->get('show_vr');
            $shouldShowGravure = Auth::user()->settings->get('show_gravure');
            $shouldShowMinors = Auth::user()->settings->get('show_minors');

            $IdsToHide = [];

            if (!$shouldShowJav->value) {
                $IdsToHide[] = 1;
            }

            if (!$shouldShowVr->value) {
                $IdsToHide[] = 4;
            }

            if (!$shouldShowGravure->value) {
                $IdsToHide[] = 2;
            }

            if (!$shouldShowMinors->value) {
                $IdsToHide[] = 3;
            }

            if (count($IdsToHide) > 0) {
                Log::info('Hiding movies of type: ' . implode(', ', $IdsToHide));
                return $query->whereNotIn('type_id', $IdsToHide);
            }

            Log::info('IDs to hide: ' . implode(', ', $IdsToHide));
            Log::info('Should show JAV: ' . $shouldShowJav);
            Log::info('Should show VR: ' . $shouldShowVr);
            Log::info('Should show Gravure: ' . $shouldShowGravure);
            Log::info('Should show Minors: ' . $shouldShowMinors);

            return $query;
        }

        // If not connected, we always hide JAV and VR titles
        return $query->whereNot('type_id', [1, 4]);
    }
}
