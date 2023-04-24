<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class LinkPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:link {permission} {role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Link a permission to a role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $permission = $this->argument('permission');
        $role = $this->argument('role');

        $permission = Permission::findByName($permission);
        $role = Role::findByName($role);

        $role->givePermissionTo($permission);

        $this->info("Permission {$permission->name} linked to role {$role->name}");
    }
}
