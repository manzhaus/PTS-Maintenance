<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceLog;
use App\Models\Asset;
use App\Models\AssetMaintenance;
use App\Models\BudgetRequest;
use App\Models\User;
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

        // 2. Widget: Pending Budget Requests Count
        $pendingRequestsCount = BudgetRequest::where('status', 'Submitted')->count();

        // 3. Process Data for Comparison Tables
        $allSupervisors = User::where('role', 'supervisor')->get();
        $ptsPerformance = [];
        $overBudgetAlerts = [];

        foreach ($allSupervisors as $s) {
            $spent = (float)$this->calculateTotalSpent($s->pts_lokasi, $now);
            $limit = (float)$s->budget_bulanan_maintenance;
            $percent = $limit > 0 ? ($spent / $limit) * 100 : 0;

            // Data untuk jadual perbandingan
            $ptsPerformance[] = [
                'pts_lokasi' => $s->pts_lokasi,
                'total' => $spent,
                'limit' => $limit,
                'percent' => $percent,
                'baki' => $limit - $spent
            ];

            // Data untuk Alert (Over 80%)
            if ($percent >= 80) {
                $overBudgetAlerts[] = [
                    'pts' => $s->pts_lokasi,
                    'percent' => $percent,
                    'baki' => $limit - $spent
                ];
            }
        }

        // --- Transformation for View ---
        
        // Table 1: Top 3 Spending (Sorted by RM)
        $topPTS = collect($ptsPerformance)
    ->sortByDesc('total')
    ->take(3)
    ->values()
    ->toArray();

        // Table 2: Top 3 Utilization (Sorted by %)
        $topUtilization = collect($ptsPerformance)
    ->sortByDesc('percent')
    ->take(3)
    ->values()
    ->toArray();

        // Sort alerts by highest percentage
        $overBudgetAlerts = collect($overBudgetAlerts)
    ->sortByDesc('percent')
    ->take(3)
    ->values()
    ->toArray();

        // Placeholder untuk mengelakkan ralat blade (kerana Global Budget dibuang)
        $peratusBudget = 0; 
        $totalHqBudget = 0;

        return view('dashboard.admin', compact(
            'totalKosBulanIni', 
            'topPTS', 
            'topUtilization',
            'peratusBudget',
            'totalHqBudget',
            'pendingRequestsCount',
            'overBudgetAlerts'
        ));
    }

    /**
     * Logic for Supervisor
     */
    private function supervisorDashboard($user, $now)
    {
        $myLocation = $user->pts_lokasi;
        $startOfMonth = $now->copy()->startOfMonth();
        $sevenDaysAgo = $now->copy()->subDays(7);

        // --- 1. DATA FOR WIDGETS (MONTHLY) ---
        
        // Use helper to get total monthly spent for this specific PTS
        $totalSpent = $this->calculateTotalSpent($myLocation, $now);

        // Dynamic Budget from User Table
        $monthlyLimit = $user->budget_bulanan_maintenance;
        $bakiBudget = $monthlyLimit - $totalSpent;

        // Individual sums for the specific widgets
        $kosLori = maintenancelog::whereHas('lorry', function($q) use ($myLocation) {
                $q->where('pts_lokasi', $myLocation);
            })
            ->where('tarikh', '>=', $startOfMonth)
            ->sum('kos_rm');

        $kosAsetLain = AssetMaintenance::whereHas('asset', function($q) use ($myLocation) {
                $q->where('pts_lokasi', $myLocation);
            })
            ->where('tarikh', '>=', $startOfMonth)
            ->sum('kos_rm');


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
            'myLocation',
            'monthlyLimit'
        ));
    }

    /**
     * Helper: Calculate total spent (Lorry + Assets) for a specific location and month
     */
    private function calculateTotalSpent($location, $now)
    {
        $lorry = maintenancelog::whereHas('lorry', fn($q) => $q->where('pts_lokasi', $location))
            ->whereMonth('tarikh', $now->month)
            ->whereYear('tarikh', $now->year)
            ->sum('kos_rm');
            
        $asset = AssetMaintenance::whereHas('asset', fn($q) => $q->where('pts_lokasi', $location))
            ->whereMonth('tarikh', $now->month)
            ->whereYear('tarikh', $now->year)
            ->sum('kos_rm');
            
        return $lorry + $asset;
    }

    /**
     * Admin Action: Update the Base Budget for a PTS
     */
    public function updateBudget(Request $request, User $user)
    {
        $request->validate(['new_budget' => 'required|numeric|min:0']);
        
        $user->update([
            'budget_bulanan_maintenance' => $request->new_budget
        ]);

        return back()->with('success', "Budget for {$user->pts_lokasi} has been updated!");
    }
}