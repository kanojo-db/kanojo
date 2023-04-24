<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Series;
use League\Fractal\TransformerAbstract;

class SeriesIDTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Series $series)
    {
        return [
            'id' => $series->id,
        ];
    }
}
