<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AssetMaintenance;
use App\Models\Asset;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MaintenanceApiController extends Controller
{
    public function getSummary(Request $request, $id)
    {
        // Ambil bulan dari query string (?month=2026-04), default bulan semasa
        $monthStr = $request->query('month', Carbon::now()->format('Y-m'));
        $date = Carbon::parse($monthStr);

        // Cari semua aset milik PTS (ID) tertentu dan jumlahkan kos penyelenggaraan
        // Nota: Pastikan model Asset anda mempunyai 'pts_id' atau 'location'
        $totalKos = AssetMaintenance::whereHas('asset', function($q) use ($id) {
                $q->where('pts_id', $id); // Sesuaikan kolum ikut database anda
            })
            ->whereYear('tarikh', $date->year)
            ->whereMonth('tarikh', $date->month)
            ->sum('kos_rm');

        return response()->json([
            'pts_id' => (int)$id,
            'month' => $monthStr,
            'total_maintenance_cost' => (float)$totalKos,
            'currency' => 'RM'
        ], 200);
    }
}