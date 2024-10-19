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
        $permissions = [
            'view machines',
            'create machines',
            'edit machines',
            'delete machines',
            'view alerts',
            'delete alerts',
            'view tasks',
            'edit tasks',
            'delete tasks',
            'view production',
            'create production',
            'edit production',
            'delete production',
            'view sensor_readings',
            'delete sensor_readings',
            'view energy_usage',
            'create energy_usage',
            'edit energy_usage',
            'delete energy_usage',
            'view users',
            'create users',
            'edit users',
            'delete users',
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',
            'view defects',
            'create defects',
            'edit defects',
            'delete defects',
            'login',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $roleOperator = Role::create(['name' => 'operator']);
        $roleOperator->givePermissionTo([
            'view machines',
            'view alerts',
            'view production',
            'view sensor_readings',
            'view energy_usage',
            'view defects',
            'login'
        ]);

        $roleManager = Role::create(['name' => 'manager']);
        $roleManager->givePermissionTo([
            'view machines',
            'view alerts',
            'view production',
            'create production',
            'edit production',
            'view sensor_readings',
            'view energy_usage',
            'create energy_usage',
            'create defects',
            'view users',
            'create users',
            'edit users',
            'delete users',
            'login'
        ]);

        $roleAdmin = Role::create(['name' => 'admin']);
        $roleAdmin->givePermissionTo(Permission::all());
    }
}
