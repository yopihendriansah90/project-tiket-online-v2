<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating Super Admin User...');

        $superAdmin = User::firstOrCreate(
            [
                'email' => env('ADMIN_EMAIL', 'admin@example.com'),
            ],
            [
                'name' => env('ADMIN_NAME', 'Super Admin'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
            ]
        );

        // Tetapkan role 'Super Admin' yang sudah dibuat oleh RolesAndPermissionsSeeder
        $superAdmin->assignRole('Super Admin');

        $this->command->info('Super Admin user created and assigned role successfully.');
    }
}
