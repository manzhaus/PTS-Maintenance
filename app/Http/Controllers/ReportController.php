<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = DB::table('maintenance_logs')
            ->join('lorries', 'maintenance_logs.lorry_id', '=', 'lorries.id')
            ->join('users', 'maintenance_logs.created_by', '=', 'users.id')
            ->select(
                'lorries.no_plat',
                'lorries.model',
                'lorries.pts_lokasi',
                'users.name as admin_name',
                DB::raw('MONTHNAME(tarikh) as bulan'),
                DB::raw('YEAR(tarikh) as tahun'),
                DB::raw('SUM(kos_rm) as total_kos'),
                DB::raw('COUNT(maintenance_logs.id) as log_count'),
                DB::raw('MAX(MONTH(tarikh)) as bulan_angka')
            );

        // --- SECURITY: ROLE-BASED ACCESS ---
        if ($user->role === 'supervisor') {
            $query->where('lorries.pts_lokasi', $user->pts_lokasi);
        } elseif ($request->pts_lokasi) {
            $query->where('lorries.pts_lokasi', 'like', '%' . $request->pts_lokasi . '%');
        }

        // --- FILTERS ---
        if ($request->no_plat) $query->where('lorries.no_plat', 'like', '%' . $request->no_plat . '%');
        if ($request->tahun) $query->whereYear('tarikh', $request->tahun);
        if ($request->bulan) $query->whereMonth('tarikh', $request->bulan);

        $reports = $query->groupBy(
                'lorries.no_plat', 
                'lorries.model', 
                'lorries.pts_lokasi', 
                'users.name', 
                DB::raw('YEAR(tarikh)'), 
                DB::raw('MONTHNAME(tarikh)')
            )
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan_angka', 'desc')
            ->get();

        return Inertia::render('Reports/Index', [
            'reports' => $reports,
            // Ensure PTS is pre-filled for SV so the input isn't empty
            'filters' => array_merge($request->only(['no_plat', 'tahun', 'bulan', 'pts_lokasi']), [
                'pts_lokasi' => $user->role === 'supervisor' ? $user->pts_lokasi : $request->pts_lokasi
            ]),
            'userRole' => $user->role
        ]);
    }

    public function exportCsv(Request $request)
    {
        $user = auth()->user();

        $query = DB::table('maintenance_logs')
            ->join('lorries', 'maintenance_logs.lorry_id', '=', 'lorries.id')
            ->select('lorries.no_plat', 'tarikh', 'jenis_maintenance', 'kos_rm', 'vendor', 'lorries.pts_lokasi');

        // Apply same security to Export
        if ($user->role === 'supervisor') {
            $query->where('lorries.pts_lokasi', $user->pts_lokasi);
        }

        if ($request->no_plat) $query->where('lorries.no_plat', $request->no_plat);
        if ($request->tahun) $query->whereYear('tarikh', $request->tahun);
        if ($request->bulan) $query->whereMonth('tarikh', $request->bulan);

        $data = $query->get();

        $filename = "maintenance_report_" . now()->format('Y-m-d') . ".csv";
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No Plat', 'PTS Lokasi', 'Tarikh', 'Jenis', 'Kos (RM)', 'Vendor']);

            foreach ($data as $row) {
                fputcsv($file, [$row->no_plat, $row->pts_lokasi, $row->tarikh, $row->jenis_maintenance, $row->kos_rm, $row->vendor]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}