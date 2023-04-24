<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Represents the status of the content report.
 *
 * @typescript
 */
enum ContentReportStatus: string
{
    case Open = 'open';
    case Resolved = 'resolved';
    case Rejected = 'rejected';
    case Invalid = 'invalid';
    case InProgress = 'in_progress';
}
