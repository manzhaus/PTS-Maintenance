<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Tentukan Query Staff berdasarkan Role
        if ($user->role === 'admin') {
            // Admin nampak semua staff
            $staff = Staff::all();
            
            // Stats untuk semua lokasi
            $stats = Staff::selectRaw('pts_lokasi, SUM(gaji_asas) as total_gaji, COUNT(*) as jumlah_staff')
                ->groupBy('pts_lokasi')
                ->get();
        } else {
            // Supervisor hanya nampak staff dari lokasi PTS mereka sahaja
            $staff = Staff::where('pts_lokasi', $user->pts_lokasi)->get();
            
            // Stats hanya untuk lokasi supervisor tersebut
            $stats = Staff::selectRaw('pts_lokasi, SUM(gaji_asas) as total_gaji, COUNT(*) as jumlah_staff')
                ->where('pts_lokasi', $user->pts_lokasi)
                ->groupBy('pts_lokasi')
                ->get();
        }

        return Inertia::render('Staff/Index', [
            'staff' => $staff,
            'stats' => $stats
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jawatan' => 'required|string',
            'gaji_asas' => 'required|numeric',
            'pts_lokasi' => 'required|string',
            'tarikh_mula_kerja' => 'required|date',
        ]);

        Staff::create($validated);

        return redirect()->back()->with('success', 'Staff berjaya didaftarkan!');
    }

    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jawatan' => 'required|string',
            'gaji_asas' => 'required|numeric',
            'pts_lokasi' => 'required|string',
            'tarikh_mula_kerja' => 'required|date',
        ]);

        $staff->update($validated);

        return redirect()->back()->with('success', 'Maklumat staff dikemaskini!');
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();

        return redirect()->back()->with('success', 'Staff berjaya dipadam!');
    }
}