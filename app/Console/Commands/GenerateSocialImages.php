<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\GenerateSocialImage;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

class GenerateSocialImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-social-images {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $class = $this->argument('model');

        /** @var Model */
        $model = new $class;

        $this->info("Generating social image for {$model}...");

        $model::chunkById(100, function ($items) {
            foreach ($items as $item) {
                $this->info("Generating social image for {$item->id}...");

                GenerateSocialImage::dispatchSync($item);
            }
        });
    }
}
