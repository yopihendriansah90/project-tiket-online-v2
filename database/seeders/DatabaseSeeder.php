<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Urutan penting:
        // 1. Buat Roles dan User Admin utama.
        // 2. (Opsional) Buat data demo jika environment adalah local/development.
        $this->call([
            RolesAndPermissionsSeeder::class,
            SuperAdminSeeder::class,
        ]);

        // Hanya jalankan DemoDataSeeder di environment lokal
        if (app()->environment('local')) {
            $this->command->info('Environment is local, running DemoDataSeeder...');
            $this->call([
                DemoDataSeeder::class,
            ]);
        }
    }
}
