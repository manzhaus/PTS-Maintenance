<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetMaintenance;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AssetMaintenanceController extends Controller
{
    public function index(Request $request, $category = 'All')
    {
        $user = auth()->user();
        
        $query = Asset::with(['maintenances' => function($q) {
            $q->orderBy('tarikh', 'desc');
        }]);

        // Filter by Location
        if ($user->role === 'supervisor') {
            $query->where('pts_lokasi', $user->pts_lokasi);
        }

        // Filter by Category
        if ($category !== 'All') {
            $query->where('category', $category);
        }

        return Inertia::render('Assets/Index', [
            'assets' => $query->get(),
            'availableCategories' => Asset::distinct()->pluck('category'),
            'currentCategory' => $category,
        ]);
    }

    public function storeRecord(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'jenis_kerja' => 'required|string',
            'kos_rm' => 'required|numeric',
            'tarikh' => 'required|date',
            'status' => 'required|in:Siap,Dalam Proses',
            'resit' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $path = $request->hasFile('resit') ? $request->file('resit')->store('receipts', 'public') : null;

        AssetMaintenance::create($request->only('asset_id', 'jenis_kerja', 'kos_rm', 'tarikh', 'status') + [
            'resit_path' => $path,
            'created_by' => auth()->id()
        ]);

        return back();
    }

    public function registerAsset(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'category' => 'required|string', // Admin can type anything here
            'next_cal' => 'nullable|date',
        ]);

        Asset::create([
            'name' => $request->name,
            'category' => $request->category,
            'pts_lokasi' => auth()->user()->pts_lokasi ?? 'Shah Alam',
            'metadata' => $request->next_cal ? ['tarikh_kalibrasi_seterusnya' => $request->next_cal] : null,
        ]);

        return back()->with('success', 'Aset berjaya didaftarkan.');
    }

    public function updateRecord(Request $request, $id)
{
    // Load the record with its parent asset
    $record = AssetMaintenance::with('asset')->findOrFail($id);
    
    $request->validate([
        'jenis_kerja' => 'required|string',
        'kos_rm' => 'required|numeric',
        'tarikh' => 'required|date',
        'status' => 'required|in:Siap,Dalam Proses',
        'next_cal' => 'nullable|date', // Validate the new field
    ]);

    // 1. Update the Maintenance Log
    $record->update($request->only('jenis_kerja', 'kos_rm', 'tarikh', 'status'));

    // 2. If it's a Weighbridge, update the Asset's metadata
    if ($record->asset->category === 'Weighbridge' && $request->has('next_cal')) {
        $asset = $record->asset;
        $metadata = $asset->metadata ?? [];
        $metadata['tarikh_kalibrasi_seterusnya'] = $request->next_cal;
        
        $asset->metadata = $metadata;
        $asset->save();
    }

    return back()->with('success', 'Rekod dan maklumat aset dikemaskini.');
}

public function destroyRecord($id)
{
    $record = AssetMaintenance::findOrFail($id);
    $record->delete();
    
    return back()->with('success', 'Rekod berjaya dipadam.');
}
}