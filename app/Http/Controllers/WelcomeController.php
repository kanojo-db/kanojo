<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Person;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Welcome', [
            'popularModels' => Person::with(['media'])->take(25)->get(),
            'popularMovies' => Movie::latest()
                ->with([
                    'media',
                    'type',
                ])
                ->take(25)
                ->get(),
            'latestMovies' => Movie::latest()
                ->with([
                    'media',
                    'type',
                ])
                ->latest()
                ->take(25)
                ->get(),
            'recentlyUpdatedMovies' => Movie::latest('updated_at')
                ->with([
                    'media',
                    'type',
                ])
                ->take(25)
                ->get(),
            'recentlyReleasedMovies' => Movie::latest('release_date')
                ->where('release_date', '<=', now()->toDateString())
                ->with([
                    'media',
                    'type',
                ])
                ->take(25)
                ->get(),
            'movieCount' => Movie::count(),
            'modelCount' => Person::count(),
            'tagCount' => \Spatie\Tags\Tag::count(),
            'topUsers' => function (): mixed {
                $topUsers = User::orderBy('audits_count', 'desc')
                    ->withCount('audits')
                    ->take(10)
                    ->get();

                return $topUsers->map(function (User $user): mixed {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'total_audits' => $user->audits_count,
                        'audits_this_week' => $user->audits()->where('created_at', '>=', now()->subWeek())->count(),
                    ];
                });
            },
        ]);
    }
}
