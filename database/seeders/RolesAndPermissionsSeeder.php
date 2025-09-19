<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Example permissions
        Permission::firstOrCreate(['name' => 'orders.index']);
        Permission::firstOrCreate(['name' => 'orders.create']);
        Permission::firstOrCreate(['name' => 'orders.edit']);
        Permission::firstOrCreate(['name' => 'orders.delete']);

        // Create roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $cashier = Role::firstOrCreate(['name' => 'cashier']);
        $waiter = Role::firstOrCreate(['name' => 'waiter']);

        // Assign permissions
        $admin->givePermissionTo(Permission::all()); // admin has all permissions
        $cashier->givePermissionTo(['orders.index', 'orders.create']);
        $waiter->givePermissionTo(['orders.index']);
    }
}
