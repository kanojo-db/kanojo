<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $createSeriesPerm = Permission::findByName('create series');
        $editSeriesPerm = Permission::findByName('edit series');
        $deleteSeriesPerm = Permission::findByName('delete series');

        $adminRole = Role::findByName('admin');
        $adminRole->syncPermissions([]);

        $moderatorRole = Role::findByName('moderator');
        $moderatorRole->syncPermissions([
            $createSeriesPerm,
            $editSeriesPerm,
            $deleteSeriesPerm,
        ]);

        $userRole = Role::findByName('user');
        $userRole->syncPermissions([
            $createSeriesPerm,
            $editSeriesPerm,
        ]);

        $bannedRole = Role::findByName('banned');
        $bannedRole->syncPermissions([]);
    }
}
