<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;

class MoviePreviewController extends Controller
{
    /**
     * Display a preview of a movie for the given movie ID. Intended to be used
     * for social media previews.
     */
    public function show(Request $request, Movie $movie)
    {
        // Check if the image exists
        $disk = Storage::disk('r2');

        if ($disk->exists($this->getImagePath($movie))) {
            $fileDate = $disk->lastModified($this->getImagePath($movie));
            $movieDate = $movie->updated_at->timestamp;

            if ($fileDate > $movieDate) {
                return response($disk->get($this->getImagePath($movie)), 200, [
                    'Content-Type' => 'image/webp',
                ]);
            }

            $disk->delete($this->getImagePath($movie));
        }

        $screenshot = Browsershot::url(route('movies.preview.internal', $movie))
            ->setChromePath('/usr/bin/chromium')
            ->noSandbox()
            ->showBackground()
            ->waitUntilNetworkIdle()
            ->windowSize(1200, 630)
            ->setScreenshotType('webp', 100)
            ->screenshot($this->getImagePath($movie));

        $disk->put($this->getImagePath($movie), $screenshot);

        return response($screenshot, 200, [
            'Content-Type' => 'image/webp',
        ]);
    }

    private function getImagePath(Movie $movie): string
    {
        return "social/movie/{$movie->id}.webp";
    }
}
