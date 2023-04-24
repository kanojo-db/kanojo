<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Studio;
use League\Fractal\TransformerAbstract;

class StudioIDTransformer extends TransformerAbstract
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
        ];
    }
}
