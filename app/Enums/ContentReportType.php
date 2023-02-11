<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Represents the type of the content report.
 * 
 * @typescript
 */
enum ContentReportType: string
{
    case BadImage = 'bad_image';
    case Duplicate = 'duplicate';
    case Incorrect = 'incorrect';
    case Other = 'other';
    case Spam = 'spam';
}
