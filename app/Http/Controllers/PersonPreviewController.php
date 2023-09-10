<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;

class PersonPreviewController extends Controller
{
    /**
     * Display a preview of a person for the given ID. Intended to be used
     * for social media previews.
     */
    public function show(Request $request, Person $model)
    {
        // Check if the image exists
        $disk = Storage::disk('r2');

        if ($disk->exists($this->getImagePath($model))) {
            return response($disk->get($this->getImagePath($model)), 200, [
                'Content-Type' => 'image/webp',
            ]);
        }

        $screenshot = Browsershot::url(route('models.preview.internal', $model))
            ->setChromePath('/usr/bin/chromium')
            ->noSandbox()
            ->showBackground()
            ->windowSize(1200, 630)
            ->setScreenshotType('webp', 100)
            ->screenshot($this->getImagePath($model));

        $disk->put($this->getImagePath($model), $screenshot);

        return response($screenshot, 200, [
            'Content-Type' => 'image/webp',
        ]);
    }

    private function getImagePath(Person $model): string
    {
        return "social/model/{$model->id}.webp";
    }
}
