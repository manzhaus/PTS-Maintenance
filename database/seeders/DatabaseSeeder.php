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
    // Create an HQ Admin
    \App\Models\User::create([
        'name' => 'HQ Admin',
        'email' => 'admin@pts.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
        'pts_lokasi' => null, // Admins see all
    ]);

    // Create a Shah Alam Supervisor
    \App\Models\User::create([
        'name' => 'Fakhrul',
        'email' => 'sv_sa@pts.com',
        'password' => bcrypt('password'),
        'role' => 'supervisor',
        'pts_lokasi' => 'Shah Alam',
    ]);
}
}
