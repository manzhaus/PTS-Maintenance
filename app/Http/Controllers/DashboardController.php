<?php

namespace App\Http\Controllers;

use App\Models\maintenancelog;
use App\Models\Asset;
use App\Models\AssetMaintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Handle the Dashboard View based on Roles
     */
    public function index()
    {
        $user = auth()->user();
        $now = Carbon::now();

        if ($user->role === 'admin') {
            return $this->adminDashboard($now);
        }

        return $this->supervisorDashboard($user, $now);
    }

    /**
     * Logic for HQ Admin
     */
    private function adminDashboard($now)
{
    // 1. Total Kos Maintenance Bulan Ini (All PTS)
    $totalKosBulanIni = maintenancelog::whereMonth('tarikh', $now->month)
        ->whereYear('tarikh', $now->year)
        ->sum('kos_rm') + 
        AssetMaintenance::whereMonth('tarikh', $now->month)
        ->whereYear('tarikh', $now->year)
        ->sum('kos_rm');

    // 2. Top 3 PTS Kos Tertinggi (Combining Lorry + Asset costs per Location)
    // This query gets total cost grouped by the 'pts_lokasi' found in the lorries table
    $topPTS = maintenancelog::join('lorries', 'lorries.id', '=', 'maintenance_logs.lorry_id')
        ->select('lorries.pts_lokasi', DB::raw('SUM(maintenance_logs.kos_rm) as total'))
        ->groupBy('lorries.pts_lokasi')
        ->orderByDesc('total')
        ->take(3)
        ->get();

    // 3. % Budget Digunakan (Example: Total HQ Budget is RM 50,000)
    $totalHqBudget = 50000;
    $peratusBudget = ($totalHqBudget > 0) ? ($totalKosBulanIni / $totalHqBudget) * 100 : 0;

    return view('dashboard.admin', compact(
        'totalKosBulanIni', 
        'topPTS', 
        'peratusBudget',
        'totalHqBudget'
    ));
}

    /**
     * Logic for Supervisor
     */
    private function supervisorDashboard($user, $now)
{
    $myLocation = $user->pts_lokasi;
    
    // Date ranges
    $startOfMonth = $now->copy()->startOfMonth();
    $sevenDaysAgo = $now->copy()->subDays(7);

    // --- 1. DATA FOR WIDGETS (MONTHLY) ---
    
    // Total Monthly Lorry Cost
    $kosLori = maintenancelog::whereHas('lorry', function($q) use ($myLocation) {
            $q->where('pts_lokasi', $myLocation);
        })
        ->where('tarikh', '>=', $startOfMonth)
        ->sum('kos_rm');

    // Total Monthly Asset Cost
    $kosAsetLain = AssetMaintenance::with('asset')
        ->whereHas('asset', function($q) use ($myLocation) {
            $q->where('pts_lokasi', $myLocation);
        })
        ->where('tarikh', '>=', $startOfMonth)
        ->sum('kos_rm');

    // Budget Calculation (Assume RM 5000 monthly)
    $monthlyLimit = 5000;
    $bakiBudget = $monthlyLimit - ($kosLori + $kosAsetLain);


    // --- 2. DATA FOR TABLES (LAST 7 DAYS ONLY) ---

    $lorryLogs = maintenancelog::with('lorry')
        ->whereHas('lorry', function($q) use ($myLocation) {
            $q->where('pts_lokasi', $myLocation);
        })
        ->where('tarikh', '>=', $sevenDaysAgo)
        ->orderBy('tarikh', 'desc')
        ->get();

    $assetLogs = AssetMaintenance::with('asset')
        ->whereHas('asset', function($q) use ($myLocation) {
            $q->where('pts_lokasi', $myLocation);
        })
        ->where('tarikh', '>=', $sevenDaysAgo)
        ->orderBy('tarikh', 'desc')
        ->get();

    return view('dashboard.supervisor', compact(
        'lorryLogs', 
        'assetLogs', 
        'kosLori', 
        'kosAsetLain', 
        'bakiBudget',
        'myLocation'
    ));
}
}