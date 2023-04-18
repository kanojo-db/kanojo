<?php

declare(strict_types=1);

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;

/**
 * @template-extends Builder<\App\Models\KanojoMedia>
 */
class MediaBuilder extends Builder
{
    use \Cog\Laravel\Love\Reactable\ReactableEloquentBuilderTrait;
}
