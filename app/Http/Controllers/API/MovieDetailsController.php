<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Enums\MediaCollectionType;
use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Person;
use Carbon\Carbon;
use Cog\Laravel\Love\Reactant\ReactionCounter\Models\ReactionCounter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class MovieDetailsController extends Controller
{
    /**
     * Get Details
     *
     * Get the primary information about a movie.
     *
     * @group Movie
     *
     * @urlParam movie_slug required The slug of the movie, usually a product code. Example: abc-123
     *
     * @queryParam language string Pass a locale value to display translated data for the fields that
     * support it. Defaults to en. Example: jp
     */
    public function show(string $slug): JsonResponse
    {
        Log::debug('MovieDetailsController::show() called with: '.$slug);

        $movie = Movie::where('slug', $slug)->with([
            'media',
            'models',
            'models.media',
            'tags',
            'loveReactant.reactionCounters',
            'loveReactant.reactionTotal',
        ])->firstOrFail();

        /**
         * Get the language from the query string, default to en-US
         *
         * @var string
         */
        $language = request()->query('language', 'en-US');

        // We only need the id and name of the tags
        $genres = $movie->tags->map(function ($tag) use ($language) {
            return [
                'id' => $tag->getAttribute('id'),
                'name' => $tag->getAttribute('name')->getTranslation($language, true),
            ];
        });

        // Get the path to the poster
        $poster = $movie->getFirstMedia(MediaCollectionType::FrontCover->value);

        $posterUrl = $poster !== null ? $poster->getFullUrl() : null;

        $backdrop = $movie->getFirstMedia(MediaCollectionType::FullCover->value);

        $backdropUrl = $backdrop !== null ? $backdrop->getFullUrl() : null;

        // Get the vote data
        try {
            $votes = $movie->loveReactant->reactionCounters->map(function (ReactionCounter $vote): int {
                return $vote->getAttribute('count');
            });
            $total = $movie->loveReactant->reactionTotal;
        } catch (Throwable $t) {
            $votes = null;
            $total = null;
        }

        if ($movie->studio !== null) {
            $studio = [
                [
                    'id' => $movie->studio->id,
                    'name' => $movie->studio->getTranslation('name', $language, true) === '' ?
                                $movie->studio->getTranslation('name', 'ja-JP', false) :
                                $movie->studio->getTranslation('name', $language, false),
                ],
            ];
        } else {
            $studio = [];
        }

        // Load the movie's cast
        try {
            $cast = $movie->models->map(function (Person $model) use ($movie, $language) {
                // If the model has a birthdate and the movie has a release date, calculate the age of the
                // model at the time of the movie's release
                if ($model->birthdate !== null && $movie->release_date !== null) {
                    $age = Carbon::parse($movie->release_date)->diffInYears(Carbon::parse($model->birthdate));
                } else {
                    $age = null;
                }

                $profileImage = $model->getFirstMedia('profile');

                return [
                    'id' => $model->id,
                    'name' => $model->getTranslation('name', $language, true) === '' ?
                                $model->getTranslation('name', 'ja-JP', false) :
                                $model->getTranslation('name', $language, false),
                    'age' => $age,
                    'age_text' => $age !== null ? __('web.general.years_old', ['age' => $age]) : null,
                    'profile_path' => $profileImage !== null ?
                                        $profileImage->getFullUrl() :
                                        null,
                ];
            });
        } catch (Throwable $t) {
            Log::error($t->getMessage());
            Log::error($t->getTraceAsString());

            $cast = [];
        }

        // Return the movie details as JSON
        return response()->json([
            'backdrop_path' => $backdropUrl,
            'cast' => $cast,
            'genres' => $genres,
            'id' => $movie->id,
            'original_title' => $movie->getTranslation('title', 'ja-JP', false),
            'poster_path' => $posterUrl,
            'product_code' => $movie->product_code,
            'release_date' => $movie->release_date,
            'runtime' => $movie->length,
            'studios' => $studio,
            'title' => $movie->getTranslation('title', $language, true),
            'vote_average' => $votes !== null ? $votes->avg() * 100 : null,
            'vote_count' => $total !== null ? $total->getAttribute('count') : null,
        ]);
    }
}
