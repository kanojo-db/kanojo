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
     * @var array
     */
    protected $with = ['type'];

    /**
     * The attributes that should be automatically cast to specific types.
     *
     * @var array
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
    public function reports()
    {
        return $this->morphMany(ContentReport::class, 'reportable');
    }

    protected static function booted(): void
    {
        // Filter out hidden movies globally, since we don't want to show them anywhere
        static::addGlobalScope('filterHidden', function (Builder $builder): mixed {
            return $builder->filterHidden();
        });
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

    public function studio(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    public function type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MovieType::class);
    }

    /**
     * Define the media collection for movies
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollectionType::FrontCover->value)->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
        $this->addMediaCollection(MediaCollectionType::FullCover->value)->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function models(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Person::class)->withPivot('age')->withTimestamps();
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id' => (int) $this->id,
            'title_en' => $this->getTranslation('title', 'en-US'),
            'title_jp' => $this->getTranslation('title', 'ja-JP'),
            'product_code' => $this->product_code,
        ];
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
        $user = Auth::user();

        if (Auth::check() && $user != null && $user instanceof User) {
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
