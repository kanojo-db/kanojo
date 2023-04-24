<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\ContentReport;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContentReportCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public ContentReport $contentReport
    ) {
    }
}
