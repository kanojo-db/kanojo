<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class UnbanUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:unban-user {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unban a user from the application. Takes a user ID as an argument.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        /** @var User $user */
        $user = User::findOrFail($this->argument('user'));

        // Check if the user is not banned.
        if (! $user->hasRole('banned')) {
            $this->error('User is not banned.');

            return;
        }

        $this->info("Unbanning user {$user->id} ({$user->name})...");

        $user->removeRole('banned');

        $userRole = Role::findByName('user');

        $user->assignRole($userRole);

        $this->info('User unbanned.');
    }
}
