<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Cog\Contracts\Love\Reacterable\Models\Reacterable as ReacterableInterface;
use Cog\Laravel\Love\Reacterable\Models\Traits\Reacterable;
use DarkGhostHunter\Laraconfig\HasConfig;
use Spatie\Permission\Traits\HasRoles;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements ReacterableInterface
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use Reacterable;
    use HasConfig;
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
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the favorite movies for the user.
     */
    public function favorites()
    {
        return $this->belongsToMany(Movie::class, 'movie_user_favorite');
    }

    /**
     * Get the wishlisted movies for the user.
     */
    public function wishlist()
    {
        return $this->belongsToMany(Movie::class, 'movie_user_wishlist');
    }

    /**
     * Get the user's movie collection.
     */
    public function collection()
    {
        return $this->belongsToMany(Movie::class, 'movie_user_collection');
    }

    public function audits()
    {
        return $this->hasMany(Audit::class);
    }
}
