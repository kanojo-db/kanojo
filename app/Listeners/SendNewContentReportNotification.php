<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ContentReportCreated;
use App\Models\User;
use App\Notifications\ContentReportCreated as NotificationContentReportCreated;

class SendNewContentReportNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(ContentReportCreated $event): void
    {
        // Send a notification to the admins and moderators
        // that a new content report has been created.

        // Find all admins and moderators
        $users = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['admin', 'moderator']);
        })->get();

        // Send a notification to each user
        foreach ($users as $user) {
            $user->notify(new NotificationContentReportCreated($event->contentReport));
        }
    }
}
