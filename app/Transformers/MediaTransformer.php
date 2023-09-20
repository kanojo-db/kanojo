<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\KanojoMedia;
use League\Fractal\TransformerAbstract;

class MediaTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array<string, mixed>
     */
    public function transform(KanojoMedia $media): array
    {
        return [
            'aspect_ratio' => $media->getCustomProperty('width') && $media->getCustomProperty('height') ?
                $media->getCustomProperty('width') / $media->getCustomProperty('height') :
                null,
            'height' => $media->getCustomProperty('height'),
            'file_path' => $media->original_url,
            'vote_average' => 0,
            'vote_count' => 0,
            'width' => $media->getCustomProperty('width'),
        ];
    }
}
