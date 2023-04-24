<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\PopularityContract;
use App\Enums\MediaCollectionType;
use App\Traits\HasPopularity;
use App\Traits\LockColumns;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;
use Limelight\Limelight;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Spatie\Image\Image;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Person extends Model implements AuditableContract, HasMedia, PopularityContract
{
    use Auditable;
    use HasFactory;
    use Searchable;
    use InteractsWithMedia;
    use Sluggable;
    use HasPopularity;
    use LockColumns;

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
        'country_id',
        'height',
        'bust',
        'waist',
        'hip',
        'blood_type',
        'cup_size',
        'twitter_id',
        'instagram_id',
        'tiktok_id',
        'ameblo_id',
        'wikidata_id',
        'youtube_id',
        'google_id',
        'imdb_id',
        'fanza_id',
        'tmdb_id',
        'line_blog_id',
        'onlyfans_id',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var string[]
     */
    protected $with = ['gender', 'country'];

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
    protected $appends = ['poster', 'external_links', 'content_report_count', 'poster_count'];

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

        $romanizedTitle = $limelight->parse($japaneseTitle);

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
        $this->addMediaCollection(MediaCollectionType::Profile->value)
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    /**
     * Get the person's country.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Country>
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    /**
     * Get the person's gender.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Gender>
     */
    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class, 'gender_id', 'id');
    }

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
     * Get all of the person's aliases
     *
     * @return HasMany<Alias>
     */
    public function aliases(): HasMany
    {
        return $this->hasMany(Alias::class);
    }

    public function computePopularity(): float
    {
        $visits = $this->visitsDay();
        $totalVisits = Visit::where('date', now()->format('Y-m-d'))->count();

        // Set the weight of each parameter
        $viewsWeight = 0.7;
        $previousScoreWeight = 0.3;

        // Calculate individual scores for each factor
        $viewsScore = ($visits / $totalVisits) * $viewsWeight;
        $previousScore = $this->popularity * $previousScoreWeight;

        $popularityScore = $viewsScore + $previousScore;

        // Ensure we are within the bounds of 0 and 1
        $popularityScore = min(1, max(0, $popularityScore));

        return $popularityScore;
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

    /**
     * Return the movies this person has starred in, ordered by release date.
     *
     * @return LengthAwarePaginator<Movie>
     */
    public function getStarringMovies(int $per_page): LengthAwarePaginator
    {
        return Movie::whereHas('models', function ($query) {
            $query->where('person_id', $this->id);
        })
            ->orderBy('release_date', 'desc')
            ->paginate($per_page)
            ->withQueryString();
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
                return $this->getFirstMedia(MediaCollectionType::Profile->value);
            }
        );
    }

    /**
     * Adds a poster to the person.
     */
    public function addPoster(UploadedFile $file): void
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
     * Count for the number of posters.
     *
     * @return Attribute<int, never>
     */
    protected function posterCount(): Attribute
    {
        return new Attribute(
            get: function () {
                return $this->getMedia(MediaCollectionType::Profile->value)->count();
            }
        );
    }

    /**
     * Returns the person's external links based on available external IDs.
     *
     * @return Attribute<array<string, string|null>, never>
     */
    protected function externalLinks(): Attribute
    {
        return new Attribute(
            get: function () {
                return [
                    'twitter' => $this->twitter_id ? "https://twitter.com/{$this->twitter_id}" : null,
                    'instagram' => $this->instagram_id ? "https://instagram.com/{$this->instagram_id}" : null,
                    'tiktok' => $this->tiktok_id ? "https://tiktok.com/@{$this->tiktok_id}" : null,
                    'ameblo' => $this->ameblo_id ? "https://ameblo.jp/{$this->ameblo_id}" : null,
                    'wikidata' => $this->wikidata_id ? "https://www.wikidata.org/wiki/{$this->wikidata_id}" : null,
                    'youtube' => $this->youtube_id ? "https://www.youtube.com/channel/{$this->youtube_id}" : null,
                    'google' => $this->google_id ? "https://www.google.com/search?kgmid={$this->google_id}" : null,
                    'imdb' => $this->imdb_id ? "https://www.imdb.com/name/{$this->imdb_id}" : null,
                    'fanza' => $this->fanza_id ? "https://actress.dmm.co.jp/-/detail/=/actress_id={$this->fanza_id}/" : null,
                    'tmdb' => $this->tmdb_id ? "https://www.themoviedb.org/person/{$this->tmdb_id}" : null,
                    'line_blog' => $this->line_blog_id ? "https://lineblog.me/{$this->line_blog_id}" : null,
                    'onlyfans' => $this->onlyfans_id ? "https://onlyfans.com/{$this->onlyfans_id}" : null,
                ];
            }
        );
    }
}
