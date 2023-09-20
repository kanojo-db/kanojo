<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ContentReportType;
use App\Events\ContentReportCreated;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperContentReport
 */
class ContentReport extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'type',
        'message',
        'public',
        'reporter_id',
        'reportable_id',
        'reportable_type',
        'status',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'report_type' => ContentReportType::class,
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, string>
     */
    protected $dispatchesEvents = [
        'created' => ContentReportCreated::class,
    ];

    /**
     * Get the reportable model.
     *
     * @return MorphTo<\Illuminate\Database\Eloquent\Model, ContentReport>
     */
    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the reporter.
     *
     * @return BelongsTo<User, ContentReport>
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    /**
     * Scope for visible reports. Admins, moderators and the reporter can see everything.
     * Others can only see publci reports and their own reports.
     *
     * @param  Builder<ContentReport>  $query
     * @return Builder<ContentReport>
     */
    public function scopeVisible(Builder $query): Builder
    {
        // If the current user is an admin or moderator, return everything.
        if (auth()->check() && (auth()->user()?->hasRole('admin') || auth()->user()?->hasRole('moderator'))) {
            return $query;
        }

        // If the current user is not an admin or moderator, return only public reports and the user's own reports.
        return $query->where(function (Builder $query) {
            $query->where('public', true)
                ->orWhere('reporter_id', auth()->user()?->id);
        });
    }
}
