<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MovieDetailsController extends Controller
{
    /**
     * Get Details
     *
     * Get the primary information about a movie.
     *
     * @group Movie
     * @urlParam movie_slug required The slug of the movie, usually a product code. Example: abc-123
     * @queryParam language string Pass a locale value to display translated data for the fields that support it. Defaults to en. Example: jp
     *
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Movie $movie)
    {
        // Get the language from the query string, default to en-US
        $language = request()->query('language', 'en');

        // Load tags to use for genres
        $movie->load('tags');
        // We only need the id and name of the tags
        $genres = $movie->tags->map(function ($tag) use ($language) {
            return [
                'id' => $tag->id,
                'name' => $tag->getTranslation('name', $language, true),
            ];
        });

        // Load the movie's media
        $movie->load('media');
        // Get the path to the poster
        $poster = $movie->getFirstMedia('poster')->getFullUrl();

        // Get the vote data
        $movie->load('loveReactant.reactionCounters', 'loveReactant.reactionTotal');
        $votes = $movie->loveReactant->reactionCounters->map(function ($vote) {
            return $vote->count;
        });
        $total = $movie->loveReactant->reactionTotal;

        if ($movie->studio !== null) {
            $studio = [
                [
                    "id" => $movie->studio->id,
                    "name" => $movie->studio->getTranslation('name', 'en', true) === '' ? $movie->studio->getTranslation('name', 'jp', false) : $movie->studio->getTranslation('name', 'en', false),
                ]
            ];
        } else {
            $studio = [];
        }

        // Load the movie's cast
        $movie->load(['models', 'models.media']);
        $cast = $movie->models->map(function ($model) use ($movie, $language) {
            // If the model has a birthdate and the movie has a release date, calculate the age of the model at the time of the movie's release
            if ($model->birthdate !== null && $movie->release_date !== null) {
                $age = Carbon::parse($movie->release_date)->diffInYears(Carbon::parse($model->birthdate));
            } else {
                $age = null;
            }

            return [
                "id" => $model->id,
                "name" => $model->getTranslation('name', $language, true) === '' ? $model->getTranslation('name', 'jp', false) : $model->getTranslation('name', $language, false),
                "age" => $age,
                "age_text" => $age !== null ? __('web.general.years_old', ['age' => $age]) : null,
                "profile_path" => $model->getFirstMedia('profile') !== null ? $model->getFirstMedia('profile')->getFullUrl() : null,
            ];
        });

        // Return the movie details as JSON
        return response()->json([
            "cast" => $cast,
            "genres" => $genres,
            "id" => $movie->id,
            "original_title" => $movie->getTranslation('title', 'jp', false),
            "popularity" => [
                "today" => $movie->visitsDay(),
                "this_week" => $movie->visitsWeek(),
                "this_month" => $movie->visitsMonth(),
                "forever" => $movie->visitsForever(),
            ],
            "poster_path" => $poster,
            "product_code" => $movie->product_code,
            "release_date" => $movie->release_date,
            "runtime" => $movie->length,
            "studios" => $studio,
            "title" => $movie->getTranslation('title', $language, true),
            "vote_average" => $votes->avg() * 100,
            "vote_count" => $total !== null ? $total->count : 0,
        ]);
    }
}
