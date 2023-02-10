<?php

declare(strict_types=1);

namespace App\Models;

use App\DataTransferObjects\ModelData;
use App\DataTransferObjects\MovieTypeData;
use App\Enums\MediaCollectionType;
use Cog\Contracts\Love\Reactable\Models\Reactable as ReactableInterface;
use Cog\Laravel\Love\Reactable\Models\Traits\Reactable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use JordanMiguel\LaravelPopular\Traits\Visitable;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Spatie\LaravelData\DataCollection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;
use Spatie\Translatable\HasTranslations;

class Movie extends Model implements HasMedia, AuditableContract, ReactableInterface
{
    use Auditable;
    use HasFactory;
    use HasSlug;
    use HasTags;
    use HasTranslations;
    use InteractsWithMedia;
    use Reactable;
    use Searchable;
    use Visitable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'product_code',
        'release_date',
        'length',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var string[]
     */
    protected $with = ['type'];

    /**
     * The attributes that should be automatically cast to specific types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'release_date' => 'date',
        'type' => MovieTypeData::class,
        'models' => DataCollection::class.':'.ModelData::class,
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
        'deleted_at' => 'immutable_datetime',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var string[]
     */
    public $translatable = ['title'];

    /**
     * The attributes that should be cast.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<ContentReport>
     */
    public function reports(): MorphMany
    {
        return $this->morphMany(ContentReport::class, 'reportable');
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('product_code')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Production studio for the movie.
     */
    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    /**
     * Type of the movie.
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(MovieType::class);
    }

    /**
     * Define the media collection for movies
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollectionType::FrontCover->value)
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        $this->addMediaCollection(MediaCollectionType::FullCover->value)
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    /**
     * Models features in the movie.
     */
    public function models(): BelongsToMany
    {
        return $this->belongsToMany(Person::class)->withPivot('age')->withTimestamps();
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

    /**
     * Modify the query used to retrieve models when making all of the models searchable.
     */
    protected function makeAllSearchableUsing(Builder $query): Builder
    {
        return $query->with(['type', 'tags', 'studio', 'models']);
    }

    /**
     * Hides movies of a certain category according to user preferences.
     */
    public function scopeFilterHidden(Builder $query): Builder
    {
        /** @var User|null */
        $user = Auth::user();

        if (Auth::check() && $user != null) {
            $shouldShowJav = (bool) $user->settings->get('show_jav')->value;
            $shouldShowVr = (bool) $user->settings->get('show_vr')->value;
            $shouldShowGravure = (bool) $user->settings->get('show_gravure')->value;
            $shouldShowMinors = (bool) $user->settings->get('show_minors')->value;

            $idsToHide = [];

            if (! $shouldShowJav) {
                $idsToHide[] = 1;
            }

            if (! $shouldShowVr) {
                $idsToHide[] = 4;
            }

            if (! $shouldShowGravure) {
                $idsToHide[] = 2;
            }

            if (! $shouldShowMinors) {
                $idsToHide[] = 3;
            }

            if (! empty($idsToHide)) {
                return $query->whereNotIn('type_id', $idsToHide);
            }

            return $query;
        }

        // If not connected, we always hide JAV and VR titles
        return $query->whereNotIn('type_id', [1, 4]);
    }
}
