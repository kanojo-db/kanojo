<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class MediaServerPluginController extends Controller
{
    /**
     * Show the media server plugin information page.
     */
    public function __invoke(): Response
    {
        return Inertia::render('MediaServerPlugin');
    }
}
