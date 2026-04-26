<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetMaintenance;
use App\Models\User;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        // REMOVE THE $this->call(...) lines from here! 
        // They belong in DatabaseSeeder.php
        
        // Get the first user (Admin/SV) to act as the creator
        $user = User::first();

        if (!$user) {
            $this->command->info('No user found. Please run UserSeeder first!');
            return;
        }

        // 1. WEIGHBRIDGE
        $wb = Asset::create([
            'name' => 'Weighbridge Main Gate S1',
            'category' => 'Weighbridge',
            'pts_lokasi' => 'Shah Alam',
            'metadata' => [
                'tarikh_servis_terakhir' => '2026-01-10',
                'tarikh_kalibrasi_seterusnya' => '2026-07-10'
            ]
        ]);

        AssetMaintenance::create([
            'asset_id' => $wb->id,
            'jenis_kerja' => 'Kalibrasi Sensor',
            'kos_rm' => 1250.00,
            'tarikh' => '2026-04-05',
            'status' => 'Siap',
            'created_by' => $user->id
        ]);

        // 2. GENSET
        $genset = Asset::create([
            'name' => 'Genset Perkins 50kVA',
            'category' => 'Genset',
            'pts_lokasi' => 'Shah Alam',
            'metadata' => ['brand' => 'Perkins', 'power' => '50kVA']
        ]);

        AssetMaintenance::create([
            'asset_id' => $genset->id,
            'jenis_kerja' => 'Tukar Minyak Hitam & Filter',
            'kos_rm' => 450.00,
            'tarikh' => '2026-04-12',
            'status' => 'Siap',
            'created_by' => $user->id
        ]);

        // 3. FACILITY/BUILDING
        $building = Asset::create([
            'name' => 'Gudang Utama A',
            'category' => 'Bangunan',
            'pts_lokasi' => 'Shah Alam'
        ]);

        AssetMaintenance::create([
            'asset_id' => $building->id,
            'jenis_kerja' => 'Repair Bumbung Bocor',
            'kos_rm' => 3200.00,
            'tarikh' => '2026-04-20',
            'status' => 'Dalam Proses',
            'created_by' => $user->id
        ]);
    }
}