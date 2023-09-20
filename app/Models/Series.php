<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MediaCollectionType;
use App\Traits\LockColumns;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableObserver;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
 * @mixin IdeHelperSeries
 */
class Series extends Model implements AuditableContract, HasMedia
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
        'title',
        'studio_id',
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
    public $translatable = ['title'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var string[]
     */
    protected $appends = ['content_report_count'];

    /**
     * Attributes to exclude from the Audit.
     *
     * @var array<string>
     */
    protected $auditExclude = [
        'locked_columns',
        'created_at',
        'updated_at',
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
                'source' => ['slug_title', 'id'],
                'unique' => true,
                'maxLength' => '128',
            ],
        ];
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSlugTitleAttribute(): string
    {
        // If there is an English title, use that
        $englishTitle = $this->getTranslation('title', 'en-US', false);

        if ($englishTitle != null) {
            return $englishTitle;
        }

        // If not, in order to still have a correct slug, we romanize the Japanese title
        // It's not fully accurate, especially for names, but it's better than nothing
        $japaneseTitle = $this->getTranslation('title', 'ja-JP', false);

        $limelight = new Limelight();

        $romanizedTitle = $limelight->parse($japaneseTitle);

        // @phpstan-ignore-next-line -- Limelight has bad types
        return $romanizedTitle->string('romaji', ' ');
    }

    /**
     * Sets the event used to generate the slug.
     */
    public function sluggableEvent(): string
    {
        return SluggableObserver::SAVED;
    }

    /**
     * Get the movies released in the series.
     *
     * @return HasMany<Movie>
     */
    public function movies(): HasMany
    {
        return $this->hasMany(Movie::class);
    }

    /**
     * Get the studio that owns the series.
     *
     * @return BelongsTo<Studio, Series>
     */
    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
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
                return $this->reports()->visible()->whereIn('status', ['open', 'in_progress'])->count();
            }
        );
    }

    /**
     * Returns the movie's first front cover.
     *
     * @return Attribute<KanojoMedia|null, never>
     */
    protected function poster(): Attribute
    {
        return new Attribute(
            get: function () {
                return $this->getFirstMedia(MediaCollectionType::FrontCover->value);
            }
        );
    }

    /**
     * Returns the movie's first full cover.
     *
     * @return Attribute<KanojoMedia|null, never>
     */
    protected function fanart(): Attribute
    {
        return new Attribute(
            get: function () {
                return $this->getFirstMedia(MediaCollectionType::FullCover->value);
            }
        );
    }

    /**
     * Adds a poster to the series.
     */
    public function addPoster(UploadedFile $file): void
    {
        $image = Image::load($file->path());

        $this->addMedia($file)
            ->withCustomProperties([
                'width' => $image->getWidth(),
                'height' => $image->getHeight(),
            ])
            ->toMediaCollection(MediaCollectionType::FrontCover->value);
    }

    /**
     * Adds a fanart to the series.
     */
    public function addFanart(UploadedFile $file): void
    {
        $image = Image::load($file->path());

        $this->addMedia($file)
            ->withCustomProperties([
                'width' => $image->getWidth(),
                'height' => $image->getHeight(),
            ])
            ->toMediaCollection(MediaCollectionType::FullCover->value);
    }

    /**
     * Return the movies this series features, ordered by release date.
     *
     * @return LengthAwarePaginator<Movie>
     */
    public function getMovies(int $per_page): LengthAwarePaginator
    {
        return Movie::whereHas('series', function ($query) {
            $query->where('series_id', $this->id);
        })
            ->orderBy('release_date', 'desc')
            ->paginate($per_page)
            ->withQueryString();
    }
}
