<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ContentReportType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContentReport extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'type',
        'message',
    ];

    protected $casts = [
        'report_type' => ContentReportType::class,
    ];

    public function reportable()
    {
        return $this->morphTo();
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }
}
