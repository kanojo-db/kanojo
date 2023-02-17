<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Models\Audit;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<array-key, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var string[]
     */
    protected $with = ['roles'];

    /**
     * The attributes that should be automatically cast to specific types.
     *
     * @var array<array-key, mixed>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the favorite movies for the user.
     */
    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_user_favorite');
    }

    /**
     * Get the wishlisted movies for the user.
     */
    public function wishlist(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_user_wishlist');
    }

    /**
     * Get the user's movie collection.
     */
    public function collection(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_user_collection');
    }

    public function audits(): HasMany
    {
        return $this->hasMany(Audit::class);
    }
}
