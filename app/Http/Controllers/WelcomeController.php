<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Person;
use App\Models\User;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Welcome', [
            'popularModels' => Person::popular()->with(['media'])->withCount(['movies'])->take(25)->get(),
            'popularMovies' => Movie::popular()
                ->with([
                    'media',
                    'type',
                    'loveReactant.reactionTotal',
                    'loveReactant.reactions.type',
                ])
                ->take(25)
                ->get(),
            'latestMovies' => Movie::latest()
                ->with([
                    'media',
                    'type',
                    'loveReactant.reactionTotal',
                    'loveReactant.reactions.type',
                ])
                ->latest()
                ->take(25)
                ->get(),
            'recentlyUpdatedMovies' => Movie::latest('updated_at')
                ->with([
                    'media',
                    'type',
                    'loveReactant.reactionTotal',
                    'loveReactant.reactions.type',
                ])
                ->take(25)
                ->get(),
            'recentlyReleasedMovies' => Movie::recentlyReleased(Carbon::now()->format('Y-m-d'))
                ->orderBy('release_date', 'desc')
                ->with([
                    'media',
                    'type',
                    'loveReactant.reactionTotal',
                    'loveReactant.reactions.type',
                ])
                ->take(25)
                ->get(),
            'movieCount' => Movie::count(),
            'modelCount' => Person::count(),
            'tagCount' => \Spatie\Tags\Tag::count(),
            'topUsers' => function (): mixed {
                $topUsers = User::orderBy('audits_count', 'desc')
                    ->withCount('audits')
                    ->take(12)
                    ->get();

                return $topUsers;
            },
        ]);
    }
}
