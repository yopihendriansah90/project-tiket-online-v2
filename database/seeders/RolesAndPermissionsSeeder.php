<?php

namespace Database\Seeders;


use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // 1. Reset cache izin
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Buat Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $eventManagerRole = Role::firstOrCreate(['name' => 'event_manager']);
        $buyerRole = Role::firstOrCreate(['name' => 'buyer']);

        // 3. Buat Permissions Dasar (Contoh)
        Permission::firstOrCreate(['name' => 'view dashboard']);
        Permission::firstOrCreate(['name' => 'manage events']);
        Permission::firstOrCreate(['name' => 'view orders']);

        // 4. Berikan Semua Permissions kepada Super Admin (Shield akan mengurus sisanya)
        // Shield secara default akan memberikan semua izin CRUD pada semua resource ke 'super_admin'

        // 5. Buat Akun Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin'), // Ganti password ini!
            ]
        );

        // 6. Tetapkan Role Super Admin
        $superAdmin->assignRole($superAdminRole);
        $this->command->info('Super Admin created and assigned role!');

        // 7. Buat Akun Pembeli Contoh
        $buyer = User::firstOrCreate(
            ['email' => 'user@mail.com'],
            [
                'name' => 'Normal Buyer',
                'password' => Hash::make('user'), // Ganti password ini!
            ]
        );
        $buyer->assignRole($buyerRole);
    }
    
}
