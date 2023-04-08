<?php

declare(strict_types=1);

namespace App\MediaLibrary;

use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UUIDPathGenerator extends DefaultPathGenerator {
    /*
     * Get a unique base path for the given media.
     */
    protected function getBasePath(Media $media): string
    {
        $filename = $media->file_name;

        return substr($filename, 0, 2);
    }
};
