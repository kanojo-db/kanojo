<?php

declare(strict_types=1);

namespace App\MediaLibrary;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator;

class UUIDPathGenerator extends DefaultPathGenerator
{
    /*
     * Get a unique base path for the given media.
     */
    protected function getBasePath(Media $media): string
    {
        /**
         * @psalm-suppress UndefinedMagicPropertyFetch -- Exists, but does not seem to be recognized by Psalm
         */
        $filename = $media->file_name;

        return substr($filename, 0, 2);
    }
}
