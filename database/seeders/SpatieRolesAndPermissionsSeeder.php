<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SpatieRolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolesToCreate = [
            'admin'
        ];

        $permissionsToCreate = [
            'create cases',
            'update cases',
            'delete cases',
            'create items',
            'update items',
            'delete items',
            'create categories',
            'update categories',
            'delete categories',
            'create types',
            'update types',
            'delete types',
            'requestedItem viewAllRequests',
            'requestedItem updateStatus',
        ];

        $assignedPermissions = [
            'admin' => [
                'create cases',
                'update cases',
                'delete cases',
                'create items',
                'update items',
                'delete items',
                'create categories',
                'update categories',
                'delete categories',
                'create types',
                'update types',
                'delete types',
                'requestedItem viewAllRequests',
                'requestedItem updateStatus'
            ]
        ];

        foreach ($rolesToCreate as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        foreach ($permissionsToCreate as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        foreach ($assignedPermissions as $role => $permissions) {
            $role = Role::findByName($role);
            foreach ($permissions as $permission) {
                $permission = Permission::findByName($permission);
                $role->givePermissionTo($permission);
            }
        }
    }
}
