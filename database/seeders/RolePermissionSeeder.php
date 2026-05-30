<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'role view',
            'role create',
            'role edit',
            'role delete',

            'user view',
            'user create',
            'user edit',
            'user delete',

            'dashboard view',
            'games view',
            'game-results view',
            'game-results views',
            'chart-years view',
            'advertisements view',
            'notices view',
            'content-blocks view',
            'seo-pages view',
            'users view',
            'roles view',    
            'permissions view',


        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $superAdmin = Role::firstOrCreate([
            'name' => 'super admin',
            'guard_name' => 'web',
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $superAdmin->syncPermissions(Permission::all());

        $admin->syncPermissions([
            'role view',
            'role create',
            'role edit',
            'dashboard view',
        ]);

        $user = User::first();

        if ($user && ! $user->hasRole('super admin')) {
            $user->assignRole('super admin');
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}