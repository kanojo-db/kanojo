<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearSocialCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kanojo:clear-social-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears cached social media previews.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Clearing social media cache...');

        $disk = Storage::disk('r2');

        $disk->deleteDirectory('social');

        $this->info('Done!');

        return self::SUCCESS;
    }
}
