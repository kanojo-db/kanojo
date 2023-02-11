<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
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

        $adminRole = Role::create(['name' => 'admin']);
        $moderatorRole = Role::create(['name' => 'moderator']);
        $userRole = Role::create(['name' => 'user']);
        $bannedRole = Role::create(['name' => 'banned']);

        $createMoviePerm = Permission::create(['name' => 'create movie']);
        $editMoviePerm = Permission::create(['name' => 'edit movie']);
        $deleteMoviePerm = Permission::create(['name' => 'delete movie']);

        $createPersonPerm = Permission::create(['name' => 'create person']);
        $editPersonPerm = Permission::create(['name' => 'edit person']);
        $deletePersonPerm = Permission::create(['name' => 'delete person']);

        $createStudioPerm = Permission::create(['name' => 'create studio']);
        $editStudioPerm = Permission::create(['name' => 'edit studio']);
        $deleteStudioPerm = Permission::create(['name' => 'delete studio']);

        $createTagPerm = Permission::create(['name' => 'create tag']);
        $editTagPerm = Permission::create(['name' => 'edit tag']);
        $deleteTagPerm = Permission::create(['name' => 'delete tag']);

        $adminRole->syncPermissions([]);

        $moderatorRole->syncPermissions([
            $createMoviePerm,
            $editMoviePerm,
            $deleteMoviePerm,
            $createPersonPerm,
            $editPersonPerm,
            $deletePersonPerm,
            $createStudioPerm,
            $editStudioPerm,
            $deleteStudioPerm,
            $createTagPerm,
            $editTagPerm,
            $deleteTagPerm,
        ]);

        $userRole->syncPermissions([
            $createMoviePerm,
            $editMoviePerm,
            $createPersonPerm,
            $editPersonPerm,
            $createStudioPerm,
            $editStudioPerm,
            $createTagPerm,
            $editTagPerm,
        ]);

        $bannedRole->syncPermissions([]);

        $adminUser = User::findOrFail(1);
        $adminUser->assignRole('admin');
    }
}
