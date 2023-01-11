<?php

namespace App\Enums;

/**
 * Represents the type of the content report.
 */
enum ReportType: string
{
    case BadImage = 'bad_image';
    case Duplicate = 'duplicate';
    case Incorrect = 'incorrect';
    case Other = 'other';
    case Spam = 'spam';
}
