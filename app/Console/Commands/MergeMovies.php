<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Movie;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MergeMovies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:merge-movies {source} {target}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Merge two movies together.
    Takes two movie IDs as arguments.
    The source movie will be deleted, and all of its media and versions will be moved to the target movie.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Merging movies...');

        // Get the source and target movies.
        $source = $this->argument('source');
        $target = $this->argument('target');

        /** @var Movie $sourceMovie */
        $sourceMovie = Movie::with(['media', 'versions', 'collection', 'favorites', 'wishlist'])->findOrFail($source);
        /** @var Movie $targetMovie */
        $targetMovie = Movie::with(['media', 'versions', 'collection', 'favorites', 'wishlist'])->findOrFail($target);

        /** @param  \App\Models\KanojoMedia  $media */
        $sourceMovie->media->each(function ($media) use ($targetMovie) {
            /** @var \App\Models\KanojoMedia $media */
            $media->model_id = $targetMovie->id;

            $media->save();
        });

        /** @param  \App\Models\MovieVersion  $version */
        // Move the source movie's versions to the target movie.
        $sourceMovie->versions->each(function ($version) use ($targetMovie) {
            $version->movie_id = $targetMovie->id;

            $version->save();
        });

        // Move any collections, favorites, and wishlist items from the source movie to the target movie.
        DB::table('movie_user_collection')
            ->where('movie_id', $sourceMovie->id)
            ->update(['movie_id' => $targetMovie->id]);
        DB::table('movie_user_favorite')
            ->where('movie_id', $sourceMovie->id)
            ->update(['movie_id' => $targetMovie->id]);
        DB::table('movie_user_wishlist')
            ->where('movie_id', $sourceMovie->id)
            ->update(['movie_id' => $targetMovie->id]);

        $sourceMovie->delete();

        $this->info('Movies merged successfully.');
    }
}
