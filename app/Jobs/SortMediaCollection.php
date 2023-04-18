<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\KanojoMedia;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SortMediaCollection implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * The media instance.
     */
    private KanojoMedia $media;

    /**
     * Create a new job instance.
     */
    public function __construct(KanojoMedia $media)
    {
        $this->media = $media;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /** @var Collection<array-key, KanojoMedia> */
        $allMedia = KanojoMedia::where('model_id', $this->media->model_id)
            ->where('model_type', $this->media->model_type)
            ->where('collection_name', $this->media->collection_name)
            ->joinReactionTotal()
            ->orderBy('reaction_total_weight')
            ->get();

        // Get the IDs of the media in the correct order
        $mediaIds = $allMedia->pluck('id')->toArray();

        // Update the media order
        KanojoMedia::setNewOrder($mediaIds);
    }
}
