<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Alias
 *
 * @property int $id
 * @property string $alias
 * @property int $person_id
 * @property int $locked
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Alias newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Alias newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Alias query()
 * @method static \Illuminate\Database\Eloquent\Builder|Alias whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alias whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alias whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alias whereLocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alias wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Alias whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperAlias {}
}

namespace App\Models{
/**
 * App\Models\ContentReport
 *
 * @property int $id
 * @property int $reporter_id
 * @property int $reportable_id
 * @property string $reportable_type
 * @property string $type
 * @property string $message
 * @property int $public
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \App\Enums\ContentReportType $report_type
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $reportable
 * @property-read \App\Models\User $reporter
 * @method static \Illuminate\Database\Eloquent\Builder|ContentReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentReport onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentReport query()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentReport visible()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentReport whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentReport whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentReport wherePublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentReport whereReportableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentReport whereReportableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentReport whereReporterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentReport whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentReport whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentReport whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentReport withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentReport withoutTrashed()
 * @mixin \Eloquent
 */
	class IdeHelperContentReport {}
}

namespace App\Models{
/**
 * App\Models\Country
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereName($value)
 * @mixin \Eloquent
 */
	class IdeHelperCountry {}
}

namespace App\Models{
/**
 * App\Models\Gender
 *
 * @property int $id
 * @property array $name
 * @method static \Illuminate\Database\Eloquent\Builder|Gender newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gender newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gender query()
 * @method static \Illuminate\Database\Eloquent\Builder|Gender whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gender whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder|Gender whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder|Gender whereName($value)
 * @mixin \Eloquent
 */
	class IdeHelperGender {}
}

namespace App\Models{
/**
 * App\Models\KanojoMedia
 *
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property string|null $uuid
 * @property string $collection_name
 * @property string $name
 * @property string $file_name
 * @property string|null $mime_type
 * @property string $disk
 * @property string|null $conversions_disk
 * @property int $size
 * @property array $manipulations
 * @property array $custom_properties
 * @property array $generated_conversions
 * @property array $responsive_images
 * @property int|null $order_column
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $love_reactant_id
 * @property-read \Cog\Laravel\Love\Reactant\Models\Reactant|null $loveReactant
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $model
 * @method static \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, static> all($columns = ['*'])
 * @method static \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, static> get($columns = ['*'])
 * @method static \App\Builders\MediaBuilder|KanojoMedia joinReactionCounterOfType(string $reactionTypeName, ?string $alias = null)
 * @method static \App\Builders\MediaBuilder|KanojoMedia joinReactionTotal(?string $alias = null)
 * @method static \App\Builders\MediaBuilder|KanojoMedia newModelQuery()
 * @method static \App\Builders\MediaBuilder|KanojoMedia newQuery()
 * @method static \App\Builders\MediaBuilder|Media ordered()
 * @method static \App\Builders\MediaBuilder|KanojoMedia query()
 * @method static \App\Builders\MediaBuilder|KanojoMedia whereCollectionName($value)
 * @method static \App\Builders\MediaBuilder|KanojoMedia whereConversionsDisk($value)
 * @method static \App\Builders\MediaBuilder|KanojoMedia whereCreatedAt($value)
 * @method static \App\Builders\MediaBuilder|KanojoMedia whereCustomProperties($value)
 * @method static \App\Builders\MediaBuilder|KanojoMedia whereDisk($value)
 * @method static \App\Builders\MediaBuilder|KanojoMedia whereFileName($value)
 * @method static \App\Builders\MediaBuilder|KanojoMedia whereGeneratedConversions($value)
 * @method static \App\Builders\MediaBuilder|KanojoMedia whereId($value)
 * @method static \App\Builders\MediaBuilder|KanojoMedia whereLoveReactantId($value)
 * @method static \App\Builders\MediaBuilder|KanojoMedia whereManipulations($value)
 * @method static \App\Builders\MediaBuilder|KanojoMedia whereMimeType($value)
 * @method static \App\Builders\MediaBuilder|KanojoMedia whereModelId($value)
 * @method static \App\Builders\MediaBuilder|KanojoMedia whereModelType($value)
 * @method static \App\Builders\MediaBuilder|KanojoMedia whereName($value)
 * @method static \App\Builders\MediaBuilder|KanojoMedia whereNotReactedBy(\Cog\Contracts\Love\Reacterable\Models\Reacterable $reacterable, ?string $reactionTypeName = null)
 * @method static \App\Builders\MediaBuilder|KanojoMedia whereOrderColumn($value)
 * @method static \App\Builders\MediaBuilder|KanojoMedia whereReactedBy(\Cog\Contracts\Love\Reacterable\Models\Reacterable $reacterable, ?string $reactionTypeName = null)
 * @method static \App\Builders\MediaBuilder|KanojoMedia whereResponsiveImages($value)
 * @method static \App\Builders\MediaBuilder|KanojoMedia whereSize($value)
 * @method static \App\Builders\MediaBuilder|KanojoMedia whereUpdatedAt($value)
 * @method static \App\Builders\MediaBuilder|KanojoMedia whereUuid($value)
 * @mixin \Eloquent
 */
	class IdeHelperKanojoMedia {}
}

namespace App\Models{
/**
 * App\Models\Link
 *
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $linkable
 * @method static \Illuminate\Database\Eloquent\Builder|Link newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Link newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Link query()
 * @mixin \Eloquent
 */
	class IdeHelperLink {}
}

namespace App\Models{
/**
 * App\Models\MetadataLanguage
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|MetadataLanguage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MetadataLanguage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MetadataLanguage query()
 * @method static \Illuminate\Database\Eloquent\Builder|MetadataLanguage whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetadataLanguage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetadataLanguage whereName($value)
 * @mixin \Eloquent
 */
	class IdeHelperMetadataLanguage {}
}

namespace App\Models{
/**
 * App\Models\Movie
 *
 * @property int $id
 * @property array $title
 * @property string $popularity
 * @property string|null $release_date
 * @property int|null $length
 * @property int $is_vr
 * @property int $is_3d
 * @property string|null $official_website
 * @property int|null $studio_id
 * @property int|null $series_id
 * @property int|null $type_id
 * @property int|null $love_reactant_id
 * @property string|null $slug
 * @property string|null $imdb_id
 * @property string|null $tmdb_id
 * @property string|null $fanza_id
 * @property string|null $mgstage_id
 * @property string|null $dmm_id
 * @property string|null $fc2_id
 * @property string|null $eicbook_id
 * @property string|null $wikidata_id
 * @property string|null $google_id
 * @property array $locked_columns
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $collection
 * @property-read int|null $collection_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $favorites
 * @property-read int|null $favorites_count
 * @property-read string $slug_title
 * @property-read \Cog\Laravel\Love\Reactant\Models\Reactant|null $loveReactant
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\KanojoMedia> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Person> $models
 * @property-read int|null $models_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ContentReport> $reports
 * @property-read int|null $reports_count
 * @property-read \App\Models\Series|null $series
 * @property-read \App\Models\Studio|null $studio
 * @property-read \App\Models\MovieType|null $type
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MovieVersion> $versions
 * @property-read int|null $versions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Visit> $visits
 * @property-read int|null $visits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $wishlist
 * @property-read int|null $wishlist_count
 * @method static \Database\Factories\MovieFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Movie findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Movie newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Movie ofType(string $type)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie popular()
 * @method static \Illuminate\Database\Eloquent\Builder|Movie query()
 * @method static \Illuminate\Database\Eloquent\Builder|Movie recentlyReleased(string $date)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie upcoming(string $date)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereDmmId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereEicbookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereFanzaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereFc2Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereGoogleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereImdbId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereIs3d($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereIsVr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereLockedColumns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereLoveReactantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereMgstageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereOfficialWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie wherePopularity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereReleaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereStudioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereTmdbId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereWikidataId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 * @mixin \Eloquent
 */
	class IdeHelperMovie {}
}

namespace App\Models{
/**
 * App\Models\MovieType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Movie> $movies
 * @property-read int|null $movies_count
 * @method static \Database\Factories\MovieTypeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|MovieType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MovieType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MovieType query()
 * @method static \Illuminate\Database\Eloquent\Builder|MovieType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovieType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovieType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovieType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperMovieType {}
}

namespace App\Models{
/**
 * App\Models\MovieVersion
 *
 * @property int $id
 * @property string $format
 * @property string|null $release_date
 * @property string|null $product_code
 * @property string|null $barcode
 * @property int $movie_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Movie $movie
 * @method static \Illuminate\Database\Eloquent\Builder|MovieVersion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MovieVersion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MovieVersion query()
 * @method static \Illuminate\Database\Eloquent\Builder|MovieVersion whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovieVersion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovieVersion whereFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovieVersion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovieVersion whereMovieId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovieVersion whereProductCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovieVersion whereReleaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovieVersion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperMovieVersion {}
}

namespace App\Models{
/**
 * App\Models\Person
 *
 * @property int $id
 * @property array $name
 * @property int|null $gender_id
 * @property string|null $birthdate
 * @property string $popularity
 * @property int|null $country_id
 * @property string|null $career_start
 * @property string|null $career_end
 * @property int|null $height
 * @property int|null $bust
 * @property int|null $waist
 * @property int|null $hip
 * @property string|null $blood_type
 * @property string|null $cup_size
 * @property string|null $official_website
 * @property string|null $twitter_id
 * @property string|null $instagram_id
 * @property string|null $tiktok_id
 * @property string|null $ameblo_id
 * @property string|null $wikidata_id
 * @property string|null $youtube_id
 * @property string|null $google_id
 * @property string|null $imdb_id
 * @property string|null $fanza_id
 * @property string|null $tmdb_id
 * @property string|null $line_blog_id
 * @property string|null $onlyfans_id
 * @property array $locked_columns
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $dob_doubt
 * @property string|null $deleted_at
 * @property string|null $slug
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Alias> $aliases
 * @property-read int|null $aliases_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Country|null $country
 * @property-read \App\Models\Gender|null $gender
 * @property-read string $slug_name
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\KanojoMedia> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Movie> $movies
 * @property-read int|null $movies_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ContentReport> $reports
 * @property-read int|null $reports_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Visit> $visits
 * @property-read int|null $visits_count
 * @method static \Illuminate\Database\Eloquent\Builder|Person bornBetween(string $startDate, string $endDate)
 * @method static \Database\Factories\PersonFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Person findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Person newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Person newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Person popular()
 * @method static \Illuminate\Database\Eloquent\Builder|Person query()
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereAmebloId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereBirthdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereBloodType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereBust($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereCareerEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereCareerStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereCupSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereDobDoubt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereFanzaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereGenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereGoogleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereHip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereImdbId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereInstagramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereLineBlogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereLockedColumns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereOfficialWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereOnlyfansId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person wherePopularity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereTiktokId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereTmdbId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereTwitterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereWaist($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereWikidataId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereYoutubeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 * @mixin \Eloquent
 */
	class IdeHelperPerson {}
}

namespace App\Models{
/**
 * App\Models\Scene
 *
 * @property int $id
 * @property int|null $start
 * @property int|null $duration
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Scene newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Scene newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Scene query()
 * @method static \Illuminate\Database\Eloquent\Builder|Scene whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scene whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scene whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scene whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scene whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperScene {}
}

namespace App\Models{
/**
 * App\Models\Series
 *
 * @property int $id
 * @property array $title
 * @property string|null $slug
 * @property array $locked_columns
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $studio_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read string $slug_title
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\KanojoMedia> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Movie> $movies
 * @property-read int|null $movies_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ContentReport> $reports
 * @property-read int|null $reports_count
 * @property-read \App\Models\Studio|null $studio
 * @method static \Illuminate\Database\Eloquent\Builder|Series findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Series newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Series newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Series query()
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereLockedColumns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereStudioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Series withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 * @mixin \Eloquent
 */
	class IdeHelperSeries {}
}

namespace App\Models{
/**
 * App\Models\Studio
 *
 * @property int $id
 * @property array $name
 * @property string|null $founded_date
 * @property string|null $slug
 * @property string|null $official_website
 * @property string|null $corporate_number
 * @property string|null $twitter_id
 * @property string|null $wikidata_id
 * @property string|null $google_id
 * @property array $locked_columns
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read string $slug_name
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\KanojoMedia> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Movie> $movies
 * @property-read int|null $movies_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ContentReport> $reports
 * @property-read int|null $reports_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Series> $series
 * @property-read int|null $series_count
 * @method static \Database\Factories\StudioFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Studio findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Studio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Studio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Studio query()
 * @method static \Illuminate\Database\Eloquent\Builder|Studio whereCorporateNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Studio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Studio whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Studio whereFoundedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Studio whereGoogleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Studio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Studio whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder|Studio whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder|Studio whereLockedColumns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Studio whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Studio whereOfficialWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Studio whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Studio whereTwitterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Studio whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Studio whereWikidataId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Studio withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 * @mixin \Eloquent
 */
	class IdeHelperStudio {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $love_reacter_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $google_id
 * @property string|null $twitter_id
 * @property string|null $facebook_id
 * @property string|null $github_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Movie> $collection
 * @property-read int|null $collection_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Movie> $favorites
 * @property-read int|null $favorites_count
 * @property-read \Cog\Laravel\Love\Reacter\Models\Reacter|null $loveReacter
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Movie> $wishlist
 * @property-read int|null $wishlist_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFacebookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGithubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGoogleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLoveReacterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwitterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutTrashed()
 * @mixin \Eloquent
 */
	class IdeHelperUser {}
}

namespace App\Models{
/**
 * App\Models\Visit
 *
 * @property int $id
 * @property string $client_hash
 * @property int $visitable_id
 * @property string $visitable_type
 * @property string $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Visit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Visit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Visit query()
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereClientHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereVisitableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereVisitableType($value)
 * @mixin \Eloquent
 */
	class IdeHelperVisit {}
}

namespace Cog\Laravel\Love\Reactant\Models{
/**
 * Cog\Laravel\Love\Reactant\Models\Reactant
 *
 * @property string $id
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $reactable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ReactionCounter> $reactionCounters
 * @property-read int|null $reaction_counters_count
 * @property-read ReactionTotal|null $reactionTotal
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Reaction> $reactions
 * @property-read int|null $reactions_count
 * @method static \Illuminate\Database\Eloquent\Builder|Reactant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reactant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reactant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Reactant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reactant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reactant whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reactant whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	final class IdeHelperReactant {}
}

namespace Cog\Laravel\Love\Reacter\Models{
/**
 * Cog\Laravel\Love\Reacter\Models\Reacter
 *
 * @property string $id
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $reacterable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Reaction> $reactions
 * @property-read int|null $reactions_count
 * @method static \Illuminate\Database\Eloquent\Builder|Reacter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reacter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reacter query()
 * @method static \Illuminate\Database\Eloquent\Builder|Reacter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reacter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reacter whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reacter whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	final class IdeHelperReacter {}
}

namespace Cog\Laravel\Love\Reaction\Models{
/**
 * Cog\Laravel\Love\Reaction\Models\Reaction
 *
 * @property string $id
 * @property int $reactant_id
 * @property int $reacter_id
 * @property int $reaction_type_id
 * @property float $rate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Reactant $reactant
 * @property-read Reacter $reacter
 * @property-read ReactionType $type
 * @method static \Illuminate\Database\Eloquent\Builder|Reaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Reaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reaction whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reaction whereReactantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reaction whereReacterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reaction whereReactionTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reaction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	final class IdeHelperReaction {}
}

namespace Cog\Laravel\Love\ReactionType\Models{
/**
 * Cog\Laravel\Love\ReactionType\Models\ReactionType
 *
 * @property string $id
 * @property string $name
 * @property int $mass
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Reaction> $reactions
 * @property-read int|null $reactions_count
 * @method static \Illuminate\Database\Eloquent\Builder|ReactionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReactionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReactionType query()
 * @method static \Illuminate\Database\Eloquent\Builder|ReactionType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReactionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReactionType whereMass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReactionType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReactionType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	final class IdeHelperReactionType {}
}

namespace Illuminate\Notifications{
/**
 * Illuminate\Notifications\DatabaseNotification
 *
 * @property string $id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $notifiable
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> all($columns = ['*'])
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|DatabaseNotification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DatabaseNotification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DatabaseNotification query()
 * @method static \Illuminate\Database\Eloquent\Builder|DatabaseNotification read()
 * @method static \Illuminate\Database\Eloquent\Builder|DatabaseNotification unread()
 * @method static \Illuminate\Database\Eloquent\Builder|DatabaseNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DatabaseNotification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DatabaseNotification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DatabaseNotification whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DatabaseNotification whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DatabaseNotification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DatabaseNotification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DatabaseNotification whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperDatabaseNotification {}
}

