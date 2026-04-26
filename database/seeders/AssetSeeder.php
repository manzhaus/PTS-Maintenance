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
        $admin = User::where('role', 'admin')->first();
        $sv = User::where('role', 'supervisor')->first();

        // --- ASSETS (The 3 PTS Stations) ---
        
        $pts1 = Asset::create([
            'name' => 'Weighbridge PTS Shah Alam',
            'category' => 'Weighbridge',
            'pts_lokasi' => 'Shah Alam',
            'metadata' => ['tarikh_kalibrasi_seterusnya' => '2026-10-01']
        ]);

        $pts2 = Asset::create([
            'name' => 'Weighbridge PTS Klang',
            'category' => 'Weighbridge',
            'pts_lokasi' => 'Klang',
            'metadata' => ['tarikh_kalibrasi_seterusnya' => '2026-08-15']
        ]);

        $pts3 = Asset::create([
            'name' => 'Weighbridge PTS Subang',
            'category' => 'Weighbridge',
            'pts_lokasi' => 'Subang',
            'metadata' => ['tarikh_kalibrasi_seterusnya' => '2026-12-20']
        ]);

        AssetMaintenance::create([
            'asset_id' => $pts1->id,
            'jenis_kerja' => 'Kalibrasi Berjadual',
            'kos_rm' => 1500.00,
            'tarikh' => '2026-04-01',
            'status' => 'Siap',
            'created_by' => $admin->id
        ]);

        AssetMaintenance::create([
            'asset_id' => $pts2->id,
            'jenis_kerja' => 'Repair Load Cell',
            'kos_rm' => 850.00,
            'tarikh' => '2026-04-10',
            'status' => 'Siap',
            'created_by' => $sv->id
        ]);
    }
}