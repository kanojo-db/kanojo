<?php

declare(strict_types=1);

namespace App\Enums;

enum MovieFormat: string
{
    case DVD = 'DVD';
    case BluRay = 'Blu-ray';
    case UHDBluRay = 'UHD Blu-ray';
    case Digital = 'Digital';
    case VHS = 'VHS';
    case Laserdisc = 'Laserdisc';
    case UMD = 'UMD';
    case HDDVD = 'HD DVD';
}
