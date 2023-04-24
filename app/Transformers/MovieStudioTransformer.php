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
     * @return array
     */
    public function transform(Studio $studio)
    {
        return [
            'id' => $studio->id,
            'name' => $studio->getTranslation('name', 'en-US', false) ?
                $studio->getTranslation('name', 'en-US', false) :
                $studio->getTranslation('name', 'ja-JP', false),
        ];
    }
}
