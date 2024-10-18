<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $roleOperator = Role::create(['name' => 'operator']);
        $roleOperator->givePermissionTo([]);

        $roleManager = Role::create(['name' => 'manager']);
        $roleManager->givePermissionTo([]);

        $roleAdmin = Role::create(['name' => 'admin']);
        $roleAdmin->givePermissionTo(Permission::all());
    }
}
