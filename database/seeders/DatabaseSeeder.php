<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1 Admin
        User::updateOrCreate(
            ['email' => 'admin@pts.com'],
            ['name' => 'HQ Admin', 'password' => bcrypt('password'), 'role' => 'admin']
        );

        // 3 Supervisors (3 different PTS locations)
        User::updateOrCreate(
            ['email' => 'sv_sa@pts.com'],
            ['name' => 'Fakhrul (SV SA)', 'password' => bcrypt('password'), 'role' => 'supervisor', 'pts_lokasi' => 'Shah Alam']
        );

        User::updateOrCreate(
            ['email' => 'sv_klang@pts.com'],
            ['name' => 'Ahmad (SV KL)', 'password' => bcrypt('password'), 'role' => 'supervisor', 'pts_lokasi' => 'Klang']
        );

        User::updateOrCreate(
            ['email' => 'sv_subang@pts.com'],
            ['name' => 'Iman (SV SB)', 'password' => bcrypt('password'), 'role' => 'supervisor', 'pts_lokasi' => 'Subang']
        );

        $this->call([
            LorrySeeder::class,
            AssetSeeder::class,
        ]);
    }
}