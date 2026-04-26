<?php

namespace Database\Seeders;

use App\Models\Lorry;
use App\Models\User;
use App\Models\maintenancelog;
use Illuminate\Database\Seeder;

class LorrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Fetch the users
        $admin = User::where('role', 'admin')->first();
        $sv = User::where('role', 'supervisor')->first();

        if (!$admin || !$sv) {
            $this->command->error('Users not found. Run DatabaseSeeder first!');
            return;
        }

        // 2. Create 4 Lorries
        
        $lorry1 = Lorry::create([
            'no_plat' => 'VAB 1234',
            'model' => 'Hino 300',
            'tahun' => 2022,
            'odometer_semasa' => 151000,
            'pts_lokasi' => 'Shah Alam',
        ]);

        $lorry2 = Lorry::create([
            'no_plat' => 'BCA 5678',
            'model' => 'Isuzu NPR',
            'tahun' => 2020,
            'odometer_semasa' => 85000,
            'pts_lokasi' => 'Klang',
        ]);

        $lorry3 = Lorry::create([
            'no_plat' => 'WXY 9999',
            'model' => 'Mitsubishi Fuso',
            'tahun' => 2023,
            'odometer_semasa' => 12000,
            'pts_lokasi' => 'Subang',
        ]);

        $lorry4 = Lorry::create([
            'no_plat' => 'WWW 1111',
            'model' => 'Hino 500',
            'tahun' => 2021,
            'odometer_semasa' => 210000,
            'pts_lokasi' => 'Shah Alam',
        ]);

        // 3. Create 5 Maintenance Records (Distributed)

        // Record 1: Lorry 1 (Shah Alam) - Servis
        maintenancelog::create([
            'lorry_id' => $lorry1->id, 
            'created_by' => $sv->id,
            'tarikh' => '2026-04-10',
            'jenis_maintenance' => 'Servis',
            'kos_rm' => 450.00,
            'vendor' => 'Bengkel Ali Shah Alam',
            'odometer_masa_servis' => 150000,
        ]);

        // Record 2: Lorry 2 (Klang) - Tayar
        maintenancelog::create([
            'lorry_id' => $lorry2->id,
            'created_by' => $admin->id,
            'tarikh' => '2026-04-12',
            'jenis_maintenance' => 'Tayar',
            'kos_rm' => 1200.00,
            'vendor' => 'Klang Tyre Specialist',
            'odometer_masa_servis' => 84500,
        ]);

        // Record 3: Lorry 3 (Subang) - Bateri
        maintenancelog::create([
            'lorry_id' => $lorry4->id,
            'created_by' => $admin->id,
            'tarikh' => '2026-04-15',
            'jenis_maintenance' => 'Bateri',
            'kos_rm' => 350.00,
            'vendor' => 'Subang Battery Hub',
            'odometer_masa_servis' => 11800,
        ]);

        // --- RECURRING ISSUE TEST: Lorry 4 (Shah Alam) ---
        
        // Record 4: First visit for the issue
        maintenancelog::create([
            'lorry_id' => $lorry4->id,
            'created_by' => $sv->id,
            'tarikh' => '2026-04-18',
            'jenis_maintenance' => 'Lain-lain',
            'kos_rm' => 150.00,
            'vendor' => 'Shah Alam Auto Parts',
            'odometer_masa_servis' => 209000,
        ]);

        // Record 5: Second visit for the same issue (Recurring)
        maintenancelog::create([
            'lorry_id' => $lorry4->id,
            'created_by' => $sv->id,
            'tarikh' => '2026-04-22',
            'jenis_maintenance' => 'Servis',
            'kos_rm' => 800.00,
            'vendor' => 'Hino Authorized Service Center',
            'odometer_masa_servis' => 209500,
        ]);
    }
}