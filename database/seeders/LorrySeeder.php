<?php

namespace Database\Seeders;

use App\Models\Lorry;
use App\Models\MaintenanceLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class LorrySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role', 'supervisor')->first() ?? User::first();

        $lorry1 = Lorry::create([
            'no_plat' => 'VAB 1234',
            'model' => 'Hino 300',
            'tahun' => 2022,
            'odometer_semasa' => 150000,
            'pts_lokasi' => 'Shah Alam',
        ]);

        $lorry2 = Lorry::create([
            'no_plat' => 'BCA 5678',
            'model' => 'Isuzu NPR',
            'tahun' => 2020,
            'odometer_semasa' => 85000,
            'pts_lokasi' => 'Shah Alam',
        ]);

        Lorry::create([
            'no_plat' => 'WXY 9999',
            'model' => 'Mitsubishi Fuso',
            'tahun' => 2023,
            'odometer_semasa' => 12000,
            'pts_lokasi' => 'Klang',
        ]);
    }
}