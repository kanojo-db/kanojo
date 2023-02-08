<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Represents the type of the media collection.
 * 
 * @typescript
 */
enum MediaCollectionType: string
{
    // Movie
    case FrontCover = 'front_cover';
    case FullCover = 'full_cover';
    // People
    case Profile = 'profile';
    // Studio
    case Logo = 'logo';
}
