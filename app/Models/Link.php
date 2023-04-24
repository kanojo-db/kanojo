<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Link extends Model
{
    use HasFactory;

    /**
     * Get the parent linkable model (movie, person or studio).
     */
    public function linkable(): MorphTo
    {
        return $this->morphTo();
    }
}
