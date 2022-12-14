<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\ReportType;

class ContentReport extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'type',
        'reporter_id',
        'reportable_id',
        'reportable_type',
        'message',
    ];

    protected $casts = [
        'report_type' => ReportType::class,
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
