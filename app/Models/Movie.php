<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\PopularityContract;
use App\Enums\MediaCollectionType;
use App\Traits\HasPopularity;
use App\Traits\LockColumns;
use Carbon\Carbon;
use Cog\Contracts\Love\Reactable\Models\Reactable as ReactableInterface;
use Cog\Laravel\Love\Reactable\Models\Traits\Reactable;
use Cog\Laravel\Love\Reaction\Models\Reaction;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;
use Limelight\Limelight;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Spatie\Image\Image;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Movie extends Model implements HasMedia, AuditableContract, ReactableInterface, PopularityContract
{
    use Auditable;
    use HasFactory;
    use Sluggable;
    use InteractsWithMedia;
    use Reactable;
    use Searchable;
    use HasPopularity;
    use LockColumns;

    /**
     * Attributes
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'length',
        'series_id',
        'studio_id',
        'type_id',
        'type_id',
        'imdb_id',
        'tmdb_id',
        'fanza_id',
        'mgstage_id',
        'dmm_id',
        'fc2_id',
        'wikidata_id',
        'google_id',
        'is_vr',
        'is_3D',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var string[]
     */
    protected $with = ['type', 'versions'];

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
     * The accessors to append to the model's array form.
     *
     * @var string[]
     */
    protected $appends = [
        'poster',
        'fanart',
        'in_collection',
        'in_wishlist',
        'in_favorites',
        'poster_count',
        'fanart_count',
        'content_report_count',
        'external_links',
    ];

    /**
     * Lifecycle
     */

    /**
     * Relations
     */

    /**
     * Users who have this movie in their collection.
     *
     * @return BelongsToMany<User>
     */
    public function collection(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'movie_user_collection')->withTimestamps();
    }

    /**
     * Users who have this movie in their favorites.
     *
     * @return BelongsToMany<User>
     */
    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'movie_user_favorite')->withTimestamps();
    }

    /**
     * Models features in the movie.
     *
     * @return BelongsToMany<Person>
     */
    public function models(): BelongsToMany
    {
        return $this->belongsToMany(Person::class)->withPivot(['age', 'locked'])->withTimestamps();
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
     * Get the series for the movie.
     *
     * @return BelongsTo<Series, Movie>
     */
    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class, 'series_id');
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
     * The versions of the movie.
     *
     * @return HasMany<MovieVersion>
     */
    public function versions(): HasMany
    {
        return $this->hasMany(MovieVersion::class)->orderBy('release_date', 'asc')->orderBy('product_code', 'asc');
    }

    /**
     * Users who wishlisted the movie.
     *
     * @return BelongsToMany<User>
     */
    public function wishlist(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'movie_user_wishlist')->withTimestamps();
    }

    /**
     * Accessors & Mutators
     */

    /**
     * Adds a poster to the movie.
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
     * Adds a fanart to the movie.
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
     * Indicates if the movie is in the user's favorites.
     *
     * @return Attribute<bool, never>
     */
    protected function inFavorites(): Attribute
    {
        return new Attribute(
            get: function () {
                /** @var User|null */
                $user = Auth::user();

                if (Auth::check() && $user != null) {
                    return $user->favorites->contains($this);
                }

                return false;
            }
        );
    }

    /**
     * Indicates if the movie is in the user's wishlist.
     *
     * @return Attribute<bool, never>
     */
    protected function inWishlist(): Attribute
    {
        return new Attribute(
            get: function () {
                /** @var User|null */
                $user = Auth::user();

                if (Auth::check() && $user != null) {
                    return $user->wishlist->contains($this);
                }

                return false;
            }
        );
    }

    /**
     * Indicates if the movie is in the user's collection.
     *
     * @return Attribute<bool, never>
     */
    protected function inCollection(): Attribute
    {
        return new Attribute(
            get: function () {
                /** @var User|null */
                $user = Auth::user();

                if (Auth::check() && $user != null) {
                    return $user->collection->contains($this);
                }

                return false;
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
     * Count for the number of posters.
     *
     * @return Attribute<int, never>
     */
    protected function posterCount(): Attribute
    {
        return new Attribute(
            get: function () {
                return $this->getMedia(MediaCollectionType::FrontCover->value)->count();
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
     * Count for the number of fanarts.
     *
     * @return Attribute<int, never>
     */
    protected function fanartCount(): Attribute
    {
        return new Attribute(
            get: function () {
                return $this->getMedia(MediaCollectionType::FullCover->value)->count();
            }
        );
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
     * Get the movie's slug title.
     */
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

        return $romanizedTitle->string('romaji', ' ');
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
                    'tmdb' => $this->tmdb_id ? "https://www.themoviedb.org/person/{$this->tmdb_id}" : null,
                    'imdb' => $this->imdb_id ? "https://www.imdb.com/name/{$this->imdb_id}" : null,
                    'fanza' => $this->fanza_id ? "https://www.dmm.co.jp/digital/videoa/-/list/=/article=actress/id={$this->fanza_id}/" : null,
                    'mgstage' => $this->mgstage_id ? "https://www.mgstage.com/product/product_detail/{$this->mgstage_id}/" : null,
                    'dmm' => $this->dmm_id ? "https://www.dmm.co.jp/digital/videoa/-/detail/=/cid={$this->dmm_id}/" : null,
                    'fc2' => $this->fc2_id ? "https://adult.contents.fc2.com/article/{$this->fc2_id}/" : null,
                    'wikidata' => $this->wikidata_id ? "https://www.wikidata.org/wiki/{$this->wikidata_id}" : null,
                    'google' => $this->google_id ? "https://www.google.com/search?kgmid={$this->google_id}" : null,
                ];
            }
        );
    }

    /**
     * Scopes
     */

    /**
     * Scope a query to only include recently released movies.
     *
     * @param  Builder<Movie>  $query
     * @return Builder<Movie>
     */
    public function scopeRecentlyReleased(Builder $query, string $date): Builder
    {
        // Check if the date is valid, just in case some non-date string is passed.
        if (! Carbon::parse($date)->isValid()) {
            return $query;
        }

        return $query->where('release_date', '<=', $date);
    }

    /**
     * Scope a query to only include movies released in the future.
     *
     * @param  Builder<Movie>  $query
     * @return Builder<Movie>
     */
    public function scopeUpcoming(Builder $query, string $date): Builder
    {
        return $query->where('release_date', '>', Carbon::parse($date));
    }

    /**
     * Scope a query to only include movies of a specific type.
     *
     * @param  Builder<Movie>  $query
     * @return Builder<Movie>
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->whereHas('type', function (Builder $query) use ($type) {
            $query->where('id', $type);
        });
    }

    /**
     * Other
     */

    /**
     * Sluggable configuration.
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
        $this->addMediaCollection(MediaCollectionType::FrontCover->value)
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        $this->addMediaCollection(MediaCollectionType::FullCover->value)
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
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
        return $query->with(['type', 'studio', 'models']);
    }

    public function computePopularity(): float
    {
        $reactantFacade = $this->getLoveReactant();

        // Parameters for the popularity algorithm
        $reactionsInPastDay = $reactantFacade->reactions()->where('created_at', '>=', Carbon::now()->subDay())->count();
        $totalReactionsInPastDay = Reaction::where('created_at', '>=', Carbon::now()->subDay())->count();

        $visits = $this->visitsDay();
        $totalVisits = Visit::where('date', now()->format('Y-m-d'))->count();

        $recentFavorites = $this->favorites()->where('movie_user_favorite.created_at', '>=', Carbon::now()->subDay())->count();
        $favoritesInPastDay = DB::table('movie_user_favorite')->where('created_at', '>=', Carbon::now()->subDay())->count();

        $recentWishlists = $this->wishlist()->where('movie_user_wishlist.created_at', '>=', Carbon::now()->subDay())->count();
        $wishlistsInPastDay = DB::table('movie_user_wishlist')->where('created_at', '>=', Carbon::now()->subDay())->count();

        $totalReactions = $reactantFacade->reactions()->count();
        $totalReactionForAll = Reaction::count();

        $previousScore = $this->popularity ?? 0;

        // Set the weight of each parameter
        $votesWeight = 0.2;
        $viewsWeight = 0.15;
        $favoritesWeight = 0.15;
        $wishlistsWeight = 0.1;
        $recencyWeight = 0.2;
        $totalVotesWeight = 0.1;
        $previousScoreWeight = 0.1;

        // Calculate the recency factor based on release date
        $daysSinceRelease = Carbon::now()->diffInDays(Carbon::parse($this->release_date));
        $recencyFactor = 1 / ($daysSinceRelease + 1);

        if ($totalReactionsInPastDay === 0) {
            $totalReactionsInPastDay = 1;
        }
        $votesScore = ($reactionsInPastDay / $totalReactionsInPastDay) * $votesWeight;

        if ($totalVisits === 0) {
            $totalVisits = 1;
        }
        $viewsScore = ($visits / $totalVisits) * $viewsWeight;

        if ($favoritesInPastDay === 0) {
            $favoritesInPastDay = 1;
        }
        $favoritesScore = ($recentFavorites / $favoritesInPastDay) * $favoritesWeight;

        if ($wishlistsInPastDay === 0) {
            $wishlistsInPastDay = 1;
        }
        $wishlistsScore = ($recentWishlists / $wishlistsInPastDay) * $wishlistsWeight;

        $recencyScore = $recencyFactor * $recencyWeight;

        if ($totalReactionForAll === 0) {
            $totalReactionForAll = 1;
        }
        $totalVotesScore = ($totalReactions / $totalReactionForAll) * $totalVotesWeight;

        $previousScoreScore = $previousScore * $previousScoreWeight;

        $popularityScore = $votesScore + $viewsScore + $favoritesScore + $wishlistsScore + $recencyScore + $totalVotesScore + $previousScoreScore;

        // Ensure we are within the bounds of 0 and 1
        $popularityScore = min(1, max(0, $popularityScore));

        return $popularityScore;
    }
}
