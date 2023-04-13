<?php

declare(strict_types=1);

namespace App\Models;

use Cog\Contracts\Love\Reacterable\Models\Reacterable as ReacterableInterface;
use Cog\Laravel\Love\Reacterable\Models\Traits\Reacterable;
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

class User extends Authenticatable implements ReacterableInterface
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use Reacterable;
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
     * {@inheritDoc}
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the favorite movies for the user.
     *
     * @return BelongsToMany<Movie>
     */
    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_user_favorite');
    }

    /**
     * Get the wishlisted movies for the user.
     *
     * @return BelongsToMany<Movie>
     */
    public function wishlist(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_user_wishlist');
    }

    /**
     * Get the user's movie collection.
     *
     * @return BelongsToMany<Movie>
     */
    public function collection(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_user_collection');
    }

    /**
     * Get the user's edit history.
     *
     * @return HasMany<Audit>
     */
    public function audits(): HasMany
    {
        return $this->hasMany(Audit::class);
    }
}
