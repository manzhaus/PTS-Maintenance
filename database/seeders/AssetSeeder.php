<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetMaintenance;
use App\Models\Lorry;
use App\Models\User;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::where('role', 'admin')->value('id') ?? 1;
        $svId = User::where('role', 'supervisor')->value('id') ?? 2;

        // --- ASSETS (The 3 PTS Stations) ---
        
        $pts1 = Asset::create([
            'name' => 'Weighbridge PTS Shah Alam',
            'category' => 'Weighbridge',
            'pts_lokasi' => 'Shah Alam',
            'metadata' => ['tarikh_kalibrasi_seterusnya' => '2026-10-01']
        ]);

        $pts2 = Asset::create([
            'name' => 'Genset PTS Klang',
            'category' => 'Genset',
            'pts_lokasi' => 'Klang',
        ]);

        $pts3 = Asset::create([
            'name' => 'Fasiliti PTS Subang',
            'category' => 'Fasiliti',
            'pts_lokasi' => 'Subang',
        ]);

        AssetMaintenance::create([
            'asset_id' => $pts1->id,
            'jenis_kerja' => 'Kalibrasi Berjadual',
            'kos_rm' => 1500.00,
            'tarikh' => '2026-04-24',
            'status' => 'Siap',
            'created_by' => $adminId
        ]);

        AssetMaintenance::create([
            'asset_id' => $pts2->id,
            'jenis_kerja' => 'Servis',
            'kos_rm' => 850.00,
            'tarikh' => '2026-04-25',
            'status' => 'Siap',
            'created_by' => $svId
        ]);
    }
}