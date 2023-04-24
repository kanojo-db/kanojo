<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\MediaCollectionType;
use App\Models\KanojoMedia;
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
        $path = "social/movie/{$movie->id}.webp";

        if ($disk->exists($this->getImagePath($movie))) {
            return response($disk->get($this->getImagePath($movie)), 200, [
                'Content-Type' => 'image/webp',
            ]);
        }

        // If it doesn't exist, generate it, save it and return it
        $movie->load('media');

        /** @var KanojoMedia */
        $frontCover = $movie->getMedia(MediaCollectionType::FrontCover->value)->first();

        // For all keys of $movie->title, replace - by _
        $movie->title = str_replace('-', '_', $movie->title);

        $html = view('previews.movie', [
            'movie' => $movie,
            'frontCover' => $frontCover?->getFullUrl(),
        ])->render();

        $screenshot = Browsershot::html($html)
            ->setChromePath('/var/www/.cache/puppeteer/chrome/linux-1108766/chrome-linux/chrome')
            ->noSandbox()
            ->showBackground()
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
