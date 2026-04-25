<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceLog;
use App\Models\Lorry;
use Illuminate\Http\Request;

class MaintenanceLogController extends Controller
{
    public function store(Request $request)
{
    $validated = $request->validate([
        'lorry_id' => 'required|exists:lorries,id',
        'tarikh' => 'required|date',
        'jenis_maintenance' => 'required|string',
        'kos_rm' => 'required|numeric',
        'vendor' => 'required|string',
        'odometer_masa_servis' => 'required|integer',
        'resit_upload' => 'nullable|file|mimes:jpg,png,pdf|max:5120',
    ]);

    // Attach current user ID
    $validated['created_by'] = auth()->id();

    if ($request->hasFile('resit_upload')) {
        $validated['resit_upload'] = $request->file('resit_upload')->store('receipts', 'public');
    }

    MaintenanceLog::create($validated);

    // Update lorry odometer automatically
    \App\Models\Lorry::where('id', $request->lorry_id)
        ->update(['odometer_semasa' => $request->odometer_masa_servis]);

    return redirect()->back()->with('message', 'Log Maintenance berjaya disimpan!');
}
}
