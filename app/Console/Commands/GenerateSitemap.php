<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // TODO: Add models, movies, studios, series, etc to the sitemap.
        // TODO: Add the static pages to the sitemap.
        // TODO: Check sitemap best practices.
        SitemapGenerator::create('https://kanojodb.com')
            ->writeToFile(public_path('sitemap.xml'));
    }
}
