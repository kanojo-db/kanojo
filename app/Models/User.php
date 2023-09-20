<?php

declare(strict_types=1);

namespace App\Models;

use Chelout\RelationshipEvents\Concerns\HasBelongsToManyEvents;
use Chelout\RelationshipEvents\Traits\HasRelationshipObservables;
use Cog\Contracts\Love\Reacterable\Models\Reacterable as ReacterableInterface;
use Cog\Laravel\Love\Reacterable\Models\Traits\Reacterable;
use Cog\Laravel\Love\Reaction\Models\Reaction;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable implements MustVerifyEmail, ReacterableInterface
{
    use HasApiTokens;
    use HasBelongsToManyEvents;
    use HasFactory;
    use HasRelationshipObservables;
    use HasRoles;
    use Notifiable;
    use Reacterable;
    use SoftDeletes;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'github_id',
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
     * The accessors to append to the model's array form.
     *
     * @var string[]
     */
    protected $appends = [
        'is_administrator',
        'is_banned',
        'audits_this_week',
        'total_audits',
        'has_gravatar',
        'gravatar_url',
        'average_rating',
        'unread_notifications_count',
    ];

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
        return $this->belongsToMany(Movie::class, 'movie_user_favorite')->withTimestamps();
    }

    /**
     * Get the wishlisted movies for the user.
     *
     * @return BelongsToMany<Movie>
     */
    public function wishlist(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_user_wishlist')->withTimestamps();
    }

    /**
     * Get the user's movie collection.
     *
     * @return BelongsToMany<Movie>
     */
    public function collection(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_user_collection')->withTimestamps();
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

    /**
     * Check if the user is an administrator.
     *
     * @return Attribute<bool, never>
     */
    protected function isAdministrator(): Attribute
    {
        return new Attribute(
            get: fn () => $this->hasRole('admin')
        );
    }

    /**
     * Check if the user is banned.
     *
     * @return Attribute<bool, never>
     */
    protected function isBanned(): Attribute
    {
        return new Attribute(
            get: fn () => $this->hasRole('banned'),
        );
    }

    /** Returns the number of audits in the past week.
     *
     * @return Attribute<int, never>
     */
    protected function auditsThisWeek(): Attribute
    {
        return new Attribute(
            get: fn () => $this->audits()->where('created_at', '>=', now()->subWeek())->count()
        );
    }

    /** Returns the total number of audits
     *
     * @return Attribute<int, never>
     */
    protected function totalAudits(): Attribute
    {
        return new Attribute(
            get: fn () => $this->audits()->count()
        );
    }

    /**
     * Returns whether the user has a Gravatar.
     *
     * @return Attribute<bool, never>
     */
    protected function hasGravatar(): Attribute
    {
        return new Attribute(
            get: function () {
                $hash = md5(strtolower(trim($this->email)));

                $url = "https://www.gravatar.com/avatar/{$hash}?d=404";
                $headers = @get_headers($url);

                return ! empty($headers) && preg_match('|200|', $headers[0]);
            }
        );
    }

    /**
     * Returns the Gravatar URL for the user.
     *
     * @return Attribute<string, never>
     */
    protected function gravatarUrl(): Attribute
    {
        return new Attribute(
            get: function () {
                $hash = md5(strtolower(trim($this->email)));

                return "https://www.gravatar.com/avatar/{$hash}";
            }
        );
    }

    /**
     * Returns the average rating of the user's reactions.
     *
     * @return Attribute<int, never>
     */
    protected function averageRating(): Attribute
    {
        return new Attribute(
            get: function () {
                // We multiply by 100 to get a percentage, then round to the nearest integer.
                // @phpstan-ignore-next-line -- Laravel-love is a bit messed up with its types.
                return round((Reaction::where('reacter_id', $this->getLoveReacter()->id)->avg('rate') ?? 0) * 100);
            }
        );
    }

    /**
     * Returns the number of unread notifications.
     *
     * @return Attribute<int, never>
     */
    protected function unreadNotificationsCount(): Attribute
    {
        return new Attribute(
            get: function () {
                if ($this->id === auth()->id()) {
                    return $this->unreadNotifications()->count();
                }

                return null;
            }
        );
    }
}
