<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MediaCollectionType;
use Chelout\RelationshipEvents\Concerns\HasMorphManyEvents;
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
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Rennokki\QueryCache\Traits\QueryCacheable;
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
    use SoftDeletes;
    use QueryCacheable;
    use HasMorphManyEvents;

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
     * {@inheritDoc}
     */
    protected $casts = [
    ];

    /**
     * The attributes that are translatable.
     *
     * @var string[]
     */
    public $translatable = ['title'];

    /**
     * The default time to cache queries.
     *
     * @var int|\DateTime
     */
    public $cacheFor = 3600;

    /**
     * Invalidate the cache automatically
     * upon update in the database.
     *
     * @var bool
     */
    protected static $flushCacheOnUpdate = true;

    /**
     * When invalidating automatically on update, you can specify
     * which tags to invalidate.
     *
     * @param  string|null  $relation
     * @param  \Illuminate\Database\Eloquent\Collection<array-key, KanojoMedia>|null  $pivotedModels
     * @return string[]
     */
    public function getCacheTagsToInvalidateOnUpdate($relation = null, $pivotedModels = null): array
    {
        // When the Many To Many relations are being attached/detached or updated,
        // $pivotedModels will contain the list of models that were attached or detached.

        // Based on the roles attached or detached,
        // the following tags will be invalidated:
        // ['user:1:roles:1', 'user:1:roles:2', ..., 'user:1:roles']

        if ($relation === 'media' && $pivotedModels !== null) {
            $tags = array_reduce($pivotedModels->all(), function ($tags, KanojoMedia $media) {
                return array_merge($tags, ["user:{$this->id}:medias:{$media->id}"]);
            }, []);

            return array_merge($tags, [
                "user:{$this->id}:medias",
            ]);

        }

        return [];
    }

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
     *
     * @return BelongsTo<Studio, Movie>
     */
    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    /**
     * Type of the movie.
     *
     * @return BelongsTo<MovieType, Movie>
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
     *
     * @return BelongsToMany<Person>
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
     *
     * @param  Builder<Movie>  $query
     * @return Builder<Movie>
     */
    protected function makeAllSearchableUsing(Builder $query): Builder
    {
        return $query->with(['type', 'tags', 'studio', 'models']);
    }

    /**
     * Hides movies of a certain category according to user preferences.
     *
     * @param  Builder<Movie>  $query
     * @return Builder<Movie>
     */
    public function scopeFilterHidden(Builder $query): Builder
    {
        /** @var User|null */
        $user = Auth::user();

        if (Auth::check() && $user != null) {
            /*
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
             */

            return $query;
        }

        // If not connected, we always hide JAV and VR titles
        return $query->whereNotIn('type_id', [1, 4]);
    }
}
