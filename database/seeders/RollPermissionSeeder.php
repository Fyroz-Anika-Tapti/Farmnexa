<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

class RollPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'dashboard.view',

            'farm.view',
            'farm.create',
            'farm.edit',
            'farm.delete',

            'section.view',
            'section.create',
            'section.edit',
            'section.delete',

            'animal.view',
            'animal.create',
            'animal.edit',
            'animal.delete',

            'user.view',
            'user.create',
            'user.edit',
            'user.delete',

            'role.view',
            'role.create',
            'role.edit',
            'role.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $ownerRole = Role::where('name', 'Owner')->first();

        if ($ownerRole) {
            $ownerRole->givePermissionTo($permissions);
        }

        $user = User::first();

        if ($user) {
            $user->assignRole('Owner');
        }
    }
}