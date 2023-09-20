<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\MediaCollectionType;
use App\Models\Movie;
use App\Models\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Browsershot\Browsershot;

class GenerateSocialImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Movie|Person $item
    ) {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $url = match (true) {
            $this->item instanceof Movie => route('movies.preview.internal', $this->item),
            $this->item instanceof Person => route('models.preview.internal', $this->item),
        };

        $screenshot = Browsershot::url($url)
            ->setChromePath('/usr/bin/chromium')
            ->noSandbox()
            ->showBackground()
            ->waitUntilNetworkIdle()
            ->windowSize(1200, 630)
            ->setScreenshotType('webp', 100)
            ->screenshot();

        $this->item->addMedia($screenshot)
            ->toMediaCollection(MediaCollectionType::SocialMediaPreview->value);
    }
}
