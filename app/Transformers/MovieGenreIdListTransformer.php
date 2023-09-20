<?php

declare(strict_types=1);

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class MovieGenreIdListTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array<string, mixed>
     */
    public function transform(\Spatie\Tags\Tag $genre): array
    {
        return [
            'id' => $genre->id,
            'name' => $genre->name,
        ];
    }
}
