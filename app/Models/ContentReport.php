<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ContentReportType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'report_type' => ContentReportType::class,
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
}
