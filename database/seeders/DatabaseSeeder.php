<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Users (Admin & Supervisor)
        // Using updateOrCreate to prevent duplicate errors if you run seed without fresh
        User::updateOrCreate(
            ['email' => 'admin@pts.com'],
            [
                'name' => 'HQ Admin',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'pts_lokasi' => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'sv_sa@pts.com'],
            [
                'name' => 'Fakhrul',
                'password' => bcrypt('password'),
                'role' => 'supervisor',
                'pts_lokasi' => 'Shah Alam',
            ]
        );

        // 2. Run Asset and Lorry Seeders
        // This will call the logic we wrote in the other files
        $this->call([
            LorrySeeder::class,
            AssetSeeder::class,
        ]);
    }
}