<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\BudgetRequest;
use Illuminate\Http\Request;

class BudgetApiController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi (Had fail 5MB seperti diminta)
        $request->validate([
            'jumlah_dipohon' => 'required|numeric',
            'sebab' => 'required|string',
            'lampiran' => 'required|file|mimes:pdf,jpg,png|max:5120',
        ]);

        // 2. Simpan Lampiran
        $path = $request->file('lampiran')->store('budget_requests', 'public');

        // 3. Simpan Rekod (Gunakan auth()->id() dari token)
        $budget = BudgetRequest::create([
            'user_id' => auth()->id(),
            'pts_id' => auth()->user()->pts_id, // Andaikan user ada pts_id
            'jumlah_dipohon' => $request->jumlah_dipohon,
            'sebab' => $request->sebab,
            'lampiran_path' => $path,
            'status' => 'Submitted'
        ]);

        return response()->json([
            'message' => 'Budget request submitted successfully via API',
            'data' => $budget
        ], 201);
    }
}