<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperVisit
 */
class Visit extends Model
{
    protected $fillable = ['client_hash', 'date', 'visitable_id', 'visitable_type'];
}
