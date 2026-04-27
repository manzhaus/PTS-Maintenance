<?php

namespace App\Http\Controllers;

use App\Models\BudgetRequest;
use App\Models\User;
use App\Models\MaintenanceLog;
use App\Models\AssetMaintenance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BudgetRequestController extends Controller
{
    /**
     * 1. ADMIN: View the Budget Management Page
     */
    public function index()
    {
        $supervisors = User::where('role', 'supervisor')->get();
        
        $pendingRequests = BudgetRequest::where('status', 'Submitted')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.budget', compact('supervisors', 'pendingRequests'));
    }

    /**
     * 2. SUPERVISOR: View their own budget request history + LIVE STATS
     */
    public function myRequests()
    {
        $user = auth()->user();
        $now = Carbon::now();

        // 1. Ambil sejarah permohonan
        $myRequests = BudgetRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Kira Perbelanjaan Sebenar (Gunakan helper method di bawah)
        $totalSpent = $this->calculateCurrentSpending($user->pts_lokasi, $now);
        
        // 3. Ambil Had Bajet & Kira Baki
        $monthlyLimit = (float)$user->budget_bulanan_maintenance;
        $bakiBudget = $monthlyLimit - $totalSpent;

        // 4. Kira Peratusan
        $percentLeft = ($monthlyLimit > 0) ? ($bakiBudget / $monthlyLimit) * 100 : 0;

        return view('supervisor.budget_index', compact(
            'myRequests', 
            'bakiBudget', 
            'percentLeft', 
            'monthlyLimit'
        ));
    }

    /**
     * Helper Method: Kira jumlah belanja bulan semasa
     */
    private function calculateCurrentSpending($location, $now)
    {
        $lorry = maintenancelog::whereHas('lorry', function($q) use ($location) {
                $q->where('pts_lokasi', $location);
            })
            ->whereMonth('tarikh', $now->month)
            ->whereYear('tarikh', $now->year)
            ->sum('kos_rm');
            
        $asset = AssetMaintenance::whereHas('asset', function($q) use ($location) {
                $q->where('pts_lokasi', $location);
            })
            ->whereMonth('tarikh', $now->month)
            ->whereYear('tarikh', $now->year)
            ->sum('kos_rm');
            
        return (float)($lorry + $asset);
    }

    /**
     * 3. SUPERVISOR: Submit a new request
     */
    public function store(Request $request)
    {
        $request->validate([
            'jumlah_dipohon' => 'required|numeric|min:1',
            'sebab' => 'required|string|max:500',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,png|max:5120'
        ]);

        $path = $request->file('lampiran') ? $request->file('lampiran')->store('quotations', 'public') : null;

        BudgetRequest::create([
            'pts_lokasi' => auth()->user()->pts_lokasi,
            'jumlah_dipohon' => $request->jumlah_dipohon,
            'sebab' => $request->sebab,
            'lampiran_quotation' => $path,
            'status' => 'Submitted',
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Permohonan bajet tambahan telah dihantar kepada HQ Admin.');
    }

    /**
     * 4. ADMIN: Approve request & auto-add to monthly budget
     */
    public function approve($id)
    {
        $req = BudgetRequest::findOrFail($id);
        
        if ($req->status !== 'Submitted') {
            return back()->with('error', 'Permohonan ini telah diproses sebelum ini.');
        }

        $req->status = 'Approved';
        $req->save();

        $user = User::where('pts_lokasi', $req->pts_lokasi)
                    ->where('role', 'supervisor')
                    ->first();
        
        if ($user) {
            $user->increment('budget_bulanan_maintenance', $req->jumlah_dipohon);
        }

        return back()->with('success', 'Bajet diluluskan dan had bulanan PTS telah dikemaskini.');
    }

    /**
     * 5. ADMIN: Reject request
     */
    public function reject($id)
    {
        $req = BudgetRequest::findOrFail($id);
        $req->status = 'Rejected';
        $req->save();

        return back()->with('info', 'Permohonan bajet telah ditolak.');
    }

    /**
     * 6. ADMIN: Manually update base monthly budget
     */
    public function updateBase(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0'
        ]);

        $user = User::findOrFail($id);
        $user->budget_bulanan_maintenance = $request->amount;
        $user->save();

        return back()->with('success', "Bajet bulanan untuk {$user->pts_lokasi} telah dikemaskini.");
    }
}