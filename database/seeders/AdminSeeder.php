<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create or update the admin role
        $role = Role::firstOrCreate(
            ['name' => 'super-admin', 'guard_name' => 'web']
        );

        // 2. Sync all permissions to admin role
        $permissions = Permission::pluck('name')->toArray();
        $role->syncPermissions($permissions);

        // 3. Create or update the admin user
        $user = User::updateOrCreate(
            ['email' => 'super-admin@example.com'], // unique identifier
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // change in production
            ]
        );

        Employee::updateOrCreate(
            [
                'name'      => $user->name,
                'email'     => $user->email,
            ]
        );

        // 4. Assign admin role to user
        if (!$user->hasRole('super-admin')) {
            $user->assignRole('super-admin');
        } else {
            $user->syncRoles(['super-admin']); // just in case
        }

        // 5. Sync role permissions to user
        $user->syncPermissions($permissions);

        $this->command->info('✅ Admin user and role seeded successfully!');
    }
}
