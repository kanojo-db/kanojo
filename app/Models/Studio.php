<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MediaCollectionType;
use App\Traits\LockColumns;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableObserver;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Laravel\Scout\Searchable;
use Limelight\Limelight;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Spatie\Image\Image;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @mixin IdeHelperStudio
 */
class Studio extends Model implements AuditableContract, HasMedia
{
    use Auditable;
    use HasFactory;
    use InteractsWithMedia;
    use LockColumns;
    use Searchable;
    use Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'founded_date',
        'twitter_id',
        'wikidata_id',
        'google_id',
        'corporate_number',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'locked_columns' => 'array',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var string[]
     */
    public $translatable = ['name'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var string[]
     */
    protected $appends = [
        'logo',
        'content_report_count',
        'logo_count',
        'external_links',
    ];

    /**
     * Attributes to exclude from the Audit.
     *
     * @var array<string>
     */
    protected $auditExclude = [
        'locked_columns',
        'created_at',
        'updated_at',
        'deleted_at',
        'slug',
    ];

    /**
     * Sluggable configuration.
     *
     * @return array<string, mixed>
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['slug_name', 'id'],
                'unique' => true,
                'maxLength' => '128',
            ],
        ];
    }

    public function getSlugNameAttribute(): string
    {
        // If there is an English title, use that
        $englishTitle = $this->getTranslation('name', 'en-US', false);

        if ($englishTitle != null) {
            return $englishTitle;
        }

        // If not, in order to still have a correct slug, we romanize the Japanese title
        // It's not fully accurate, especially for names, but it's better than nothing
        $japaneseTitle = $this->getTranslation('name', 'ja-JP', false);

        $limelight = new Limelight();

        $romajiTitle = $limelight->parse($japaneseTitle);

        return $romajiTitle->string('romaji', ' ');
    }

    /**
     * Sets the event used to generate the slug.
     */
    public function sluggableEvent(): string
    {
        return SluggableObserver::SAVED;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Define the media collection for movies
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollectionType::Logo->value)
            ->acceptsMimeTypes(['image/svg+xml']);
    }

    /**
     * Get the movies released by the studio.
     *
     * @return HasMany<Movie>
     */
    public function movies(): HasMany
    {
        return $this->hasMany(Movie::class);
    }

    /**
     * Get the series released by the studio.
     *
     * @return HasMany<Series>
     */
    public function series(): HasMany
    {
        return $this->hasMany(Series::class);
    }

    /**
     * Get the content reports concerning this movie.
     *
     * @return MorphMany<ContentReport>
     */
    public function reports(): MorphMany
    {
        return $this->morphMany(ContentReport::class, 'reportable');
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
     * Count for the number of content reports.
     *
     * @return Attribute<int, never>
     */
    protected function contentReportCount(): Attribute
    {
        return new Attribute(
            get: function () {
                // Count of open and in progress reports
                return $this->reports()->visible()->whereIn('status', ['open', 'in_progress'])->count();
            }
        );
    }

    /**
     * Returns the movie's first front cover.
     *
     * @return Attribute<KanojoMedia|null, never>
     */
    protected function logo(): Attribute
    {
        return new Attribute(
            get: function () {
                return $this->getFirstMedia(MediaCollectionType::Logo->value);
            }
        );
    }

    /**
     * Adds a logo to the studio.
     */
    public function addLogo(UploadedFile $file): void
    {
        $image = Image::load($file->path());

        $this->addMedia($file)
            ->withCustomProperties([
                'width' => $image->getWidth(),
                'height' => $image->getHeight(),
            ])
            ->toMediaCollection(MediaCollectionType::Logo->value);
    }

    /**
     * Count for the number of logos.
     *
     * @return Attribute<int, never>
     */
    protected function logoCount(): Attribute
    {
        return new Attribute(
            get: function () {
                return $this->getMedia(MediaCollectionType::Logo->value)->count();
            }
        );
    }

    /**
     * Return the movies released by this studio, ordered by release date.
     *
     * @return LengthAwarePaginator<Movie>
     */
    public function getReleasedMovies(): LengthAwarePaginator
    {
        return Movie::where('studio_id', $this->id)
            ->orderBy('release_date', 'desc')
            ->paginate(25)->withQueryString();
    }

    /**
     * Return the most featured models for this studio.
     *
     * @return Collection<array-key, Person>
     */
    public function getMostFeaturedModels(): Collection
    {
        return Person::whereHas('movies', function (Builder $query) {
            $query->where('studio_id', $this->id);
        })
            ->with(['media'])
            ->withCount([
                'movies' => function (Builder $query) {
                    $query->where('studio_id', $this->id);
                },
            ])
            ->orderBy('movies_count', 'desc')
            ->take(10)
            ->get();
    }

    /**
     * Return the series with the most movies for this studio.
     *
     * @return Collection<array-key, Series>
     */
    public function getMostActiveSeries(): Collection
    {
        return Series::where('studio_id', $this->id)
            ->withCount('movies')
            ->orderBy('movies_count', 'desc')
            ->take(10)
            ->get();
    }

    /**
     * Returns the movie's external links based on available external IDs.
     *
     * @return Attribute<array<string, string|null>, never>
     */
    protected function externalLinks(): Attribute
    {
        return new Attribute(
            get: function () {
                return [
                    'twitter' => $this->twitter_id ? "https://twitter.com/{$this->twitter_id}" : null,
                    'wikidata' => $this->wikidata_id ? "https://www.wikidata.org/wiki/{$this->wikidata_id}" : null,
                    'google' => $this->google_id ? "https://www.google.com/search?kgmid={$this->google_id}" : null,
                    'corporate' => $this->corporate_number ? "https://www.houjin-bangou.nta.go.jp/henkorireki-johoto.html?selHouzinNo={$this->corporate_number}" : null,
                ];
            }
        );
    }
}
