<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Gender extends Model
{
    use HasFactory;
    use HasTranslations;

    /**
     * The attributes that are translatable.
     *
     * @var string[]
     */
    public $translatable = ['name'];
}
