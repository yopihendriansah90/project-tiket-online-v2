<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('Creating Roles...');
        // 2. Create Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $eventManagerRole = Role::firstOrCreate(['name' => 'Event Manager']);
        $userRole = Role::firstOrCreate(['name' => 'User']);

        // 3. Create Permissions
        $resources = ['attendee', 'event', 'order', 'seat', 'ticket', 'user'];
        $actions = ['view_any', 'view', 'create', 'update', 'delete', 'delete_any', 'force_delete', 'force_delete_any', 'restore', 'restore_any', 'replicate', 'reorder'];

        $permissions = [];
        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $permissions[] = Permission::firstOrCreate(['name' => $action . '_' . $resource]);
            }
        }

        // 4. Assign Permissions to Roles
        $superAdminRole->givePermissionTo($permissions);
        $eventManagerRole->givePermissionTo($permissions);

        $userPermissions = [];
        foreach ($resources as $resource) {
            $userPermissions[] = Permission::firstOrCreate(['name' => 'view_any_' . $resource]);
            $userPermissions[] = Permission::firstOrCreate(['name' => 'view_' . $resource]);
        }
        $userRole->givePermissionTo($userPermissions);

        $this->command->info('Roles and Permissions created successfully.');
    }
}