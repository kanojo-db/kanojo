<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Notifications\DatabaseNotification;
use Inertia\Inertia;
use Inertia\Response;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response|RedirectResponse
    {
        // In case the user is not logged in, we redirect them to the login page.
        if (! auth()->check() || ! auth()->user()) {
            return redirect()->route('login');
        }

        return Inertia::render('Notifications/Index', [
            'notifications' => auth()->user()->notifications()->paginate(25),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function update(DatabaseNotification $notification): RedirectResponse
    {
        $notification->markAsRead();

        return redirect()->route('notifications.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DatabaseNotification $notification): RedirectResponse
    {
        $notification->delete();

        return redirect()->route('notifications.index');
    }
}
