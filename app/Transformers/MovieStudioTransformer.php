<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Studio;
use League\Fractal\TransformerAbstract;

class MovieStudioTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array<string, mixed>
     */
    public function transform(Studio $studio): array
    {
        return [
            'id' => $studio->id,
            'name' => $studio->getTranslation('name', 'en-US', false) ?
                $studio->getTranslation('name', 'en-US', false) :
                $studio->getTranslation('name', 'ja-JP', false),
        ];
    }
}
