<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guard = 'web';

        $permissions = [
            'users.view',
            'users.create',
            'users.update',
            'users.delete',

            // Roles
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',

        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate([
                'name' => $name,
                'guard_name' => $guard,
            ]);
        }

        /**
         * Role assignments (MVP)
         * - superadmin: all permissions
         * - admin: all users permissions
         * - manager: view/update users (optional: adjust as you wish)
         * - seller/customer/support: none for admin panel by default
         */
        $superadmin = Role::where('name', 'superadmin')->where('guard_name', $guard)->first();
        $admin = Role::where('name', 'admin')->where('guard_name', $guard)->first();
        $manager = Role::where('name', 'manager')->where('guard_name', $guard)->first();

        if ($superadmin) {
            $superadmin->syncPermissions(Permission::where('guard_name', $guard)->get());
        }

        if ($admin) {
            $admin->syncPermissions(Permission::whereIn('name', [
                'users.view',
                'users.create',
                'users.update',
                'users.delete',
                'roles.view',
                'roles.create',
                'roles.update',
                'roles.delete',

            ])->where('guard_name', $guard)->get());
        }

        if ($manager) {
            $manager->syncPermissions(Permission::whereIn('name', [
                'users.view',
                'users.update',
                'roles.view',
            ])->where('guard_name', $guard)->get());
        }
    }
}
