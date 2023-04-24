<?php

declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;
use Spatie\Permission\Models\Role;

class ProcessVerifiedUser
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
    public function handle(Verified $event): void
    {
        /** @var \App\Models\User $user */
        $user = $event->user;
        $userRole = Role::findByName('user');

        $user->assignRole($userRole);
    }
}
