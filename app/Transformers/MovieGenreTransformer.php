<?php

declare(strict_types=1);

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class MovieGenreTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(\Spatie\Tags\Tag $genre)
    {
        return [
            'id' => $genre->id,
            'name' => $genre->getTranslation('name', 'en-US', true),
        ];
    }
}
