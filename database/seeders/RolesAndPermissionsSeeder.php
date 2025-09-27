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
        Role::firstOrCreate(['name' => 'Super Admin']);
        Role::firstOrCreate(['name' => 'Event Manager']);
        Role::firstOrCreate(['name' => 'User']);

        // 3. Create Permissions (contoh, karena Super Admin dapat semuanya secara otomatis)
        // Anda bisa menambahkan permission spesifik di sini jika diperlukan untuk role lain
        // Contoh: Permission::firstOrCreate(['name' => 'view events']);

        $this->command->info('Roles and Permissions created successfully.');
    }
}