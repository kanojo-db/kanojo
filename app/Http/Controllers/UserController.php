<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(User $user): Response
    {
        // Get active_tab query parameter
        $activeTab = request()->query('active_tab', 'activity');

        return Inertia::render('Profile/Show', [
            'userProfile' => $user,
            'editsCount' => $user->audits()->count(),
            'averageRating' => $user->average_rating,
            'wishlistCount' => $user->wishlist()->count(),
            'favoriteCount' => $user->favorites()->count(),
            'collectionCount' => $user->collection()->count(),
            'activityThisPastMonth' => function () use ($user) {
                return $user->audits()
                    ->where('created_at', '>=', now()->subMonth())
                    ->get()
                    ->groupBy('auditable_type')
                    ->map(function ($audits) {
                        return $audits->groupBy(function ($audit) {
                            return $audit->created_at?->format('Y-m-d');
                        })->map(function ($audits) {
                            return $audits->count();
                        })->toArray();
                    });
            },
            'recentActivity' => function () use ($user) {
                // Ignore for now, it actually exists and is likely a type issue in upstream package
                // @phpstan-ignore-next-line
                return $user->audits()
                    ->where('event', '!=', 'deleted')
                    ->with('auditable')
                    ->latest()
                    ->take(10)
                    ->get();
            },
            'items' => function () use ($activeTab, $user) {
                $items = null;

                switch ($activeTab) {
                    case 'wishlist':
                        $items = $user->wishlist()->with('media')->latest()->paginate(20);
                        break;
                    case 'favorites':
                        $items = $user->favorites()->with('media')->latest()->paginate(20);
                        break;
                    case 'collection':
                    default:
                        $items = $user->collection()->with('media')->latest()->paginate(20);
                }

                return $items;
            },
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('welcome');
    }
}
