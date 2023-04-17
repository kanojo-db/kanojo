<?php

declare(strict_types=1);

namespace App\Models;

use Rennokki\QueryCache\Traits\QueryCacheable;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class KanojoMedia extends Media
{
    use QueryCacheable;

    /**
     * The default time to cache queries.
     *
     * @var int|\DateTime
     */
    public $cacheFor = 3600;
}
