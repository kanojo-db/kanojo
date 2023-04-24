<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\MediaBuilder;
use Cog\Contracts\Love\Reactable\Models\Reactable as ReactableInterface;
use Cog\Laravel\Love\Reactable\Models\Traits\Reactable;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class KanojoMedia extends Media implements ReactableInterface
{
    use Reactable;

    public function newEloquentBuilder($query): MediaBuilder
    {
        return new MediaBuilder($query);
    }
}
