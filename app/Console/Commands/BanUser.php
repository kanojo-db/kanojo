<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class BanUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ban-user {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ban a user from the application. Takes a user ID as an argument.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        /** @var User $user */
        $user = User::findOrFail($this->argument('user'));

        // Check if the user is already banned.
        if ($user->hasRole('banned')) {
            $this->error('User is already banned.');

            return;
        }

        $this->info("Banning user {$user->id} ({$user->name})...");

        $user->syncRoles([]);

        $user->assignRole('banned');

        $this->info('User banned.');
    }
}
