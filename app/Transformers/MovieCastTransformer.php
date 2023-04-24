<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Movie;
use App\Models\Person;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class MovieCastTransformer extends TransformerAbstract
{
    private Movie $movie;

    private string $language;

    public function __construct(Movie $movie, string $language)
    {
        $this->movie = $movie;

        // If there is no language given, default to the user's preferred language
        if (! $language) {
            $language = app()->getLocale();
        }

        $this->language = $language;
    }

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Person $person)
    {
        return [
            'id' => $person->id,
            'name' => $person->getTranslation('name', $this->language, false),
            'original_name' => $person->getTranslation('name', 'ja-JP', false),
            'age' => $person->birthdate !== null && $this->movie->release_date !== null ?
                                Carbon::parse($this->movie->release_date)->diffInYears(Carbon::parse($person->birthdate)) :
                                null,
            'age_text' => $person->birthdate !== null && $this->movie->release_date !== null ?
                                __('web.general.years_old', [
                                    'age' => Carbon::parse($this->movie->release_date)->diffInYears(Carbon::parse($person->birthdate)),
                                ]) :
                                null,
            'profile_path' => $person->poster?->original_url,
            'web_path' => route('models.show', $person->slug, true),
        ];
    }
}
