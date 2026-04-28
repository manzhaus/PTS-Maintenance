<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetMaintenance;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Response;

class AssetMaintenanceController extends Controller
{
    public function index(Request $request, $category = 'All')
    {
        $user = auth()->user();
        
        $query = Asset::with(['maintenances' => function($q) {
            $q->with(['creator', 'editor'])->orderBy('tarikh', 'desc');
        }]);

        // Reusable Filter Logic
        $this->applyFilters($query, $user, $category);

        return Inertia::render('Assets/Index', [
            'assets' => $query->get(),
            'availableCategories' => Asset::distinct()->pluck('category'),
            'currentCategory' => $category,
        ]);
    }

    /**
     * Helper function to apply same filters for Index and Export
     */
    private function applyFilters($query, $user, $category)
    {
        // Filter by Location
        if ($user->role === 'supervisor') {
            $query->where('pts_lokasi', $user->pts_lokasi);
        }

        // Filter by Category
        if ($category !== 'All') {
            $query->where('category', $category);
        }
    }

    public function export(Request $request, $category = 'All')
    {
        $user = auth()->user();
        
        // Eager load maintenance records
        $query = Asset::with(['maintenances' => function($q) {
            $q->orderBy('tarikh', 'desc');
        }]);

        // Apply same filters as Index
        $this->applyFilters($query, $user, $category);

        $assets = $query->get();

        // CSV Preparation
        $fileName = "Asset_Maintenance_Report_{$category}_" . now()->format('Ymd') . ".csv";
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($assets) {
            $file = fopen('php://output', 'w');
            
            // Header Row
            fputcsv($file, ['Aset', 'Kategori', 'Lokasi', 'Jenis Kerja', 'Kos (RM)', 'Tarikh', 'Status']);

            foreach ($assets as $asset) {
                // Jika aset tiada maintenance, tunjuk info asas sahaja
                if ($asset->maintenances->isEmpty()) {
                    fputcsv($file, [$asset->name, $asset->category, $asset->pts_lokasi, '-', '-', '-', '-']);
                } else {
                    foreach ($asset->maintenances as $m) {
                        fputcsv($file, [
                            $asset->name,
                            $asset->category,
                            $asset->pts_lokasi,
                            $m->jenis_kerja,
                            $m->kos_rm,
                            $m->tarikh,
                            $m->status
                        ]);
                    }
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // --- Method storeRecord, registerAsset, updateRecord, destroyRecord kekal sama ---
    
    public function storeRecord(Request $request)
{
    $request->validate([
        'asset_id' => 'required|exists:assets,id',
        'jenis_kerja' => 'required|string',
        'kos_rm' => 'required|numeric',
        'tarikh' => 'required|date',
        'status' => 'required|in:Siap,Dalam Proses',
        'resit' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
    ]);

    // Simpan fail resit jika ada
    $path = $request->hasFile('resit') ? $request->file('resit')->store('receipts', 'public') : null;

    // 1. Simpan rekod baru ke dalam pembolehubah $record
    $record = AssetMaintenance::create($request->only('asset_id', 'jenis_kerja', 'kos_rm', 'tarikh', 'status') + [
        'resit_path' => $path,
        'created_by' => auth()->id(),
        'updated_by' => auth()->id(), // Set juga updated_by semasa create pertama kali
    ]);

    // 2. Padam bahagian $record->update(...) yang lama itu 
    // kerana data sudah disimpan semasa AssetMaintenance::create di atas.

    return back()->with('message', 'Rekod penyelenggaraan berjaya ditambah!');
}

    public function registerAsset(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'category' => 'required|string',
            'pts_lokasi' => 'required|string',
            'next_cal' => 'nullable|date',
        ]);
        
        Asset::create([
            'name' => $request->name,
            'category' => $request->category,
            'pts_lokasi' => $validated['pts_lokasi'],
            'metadata' => $request->next_cal ? ['tarikh_kalibrasi_seterusnya' => $request->next_cal] : null,
        ]);

        return back()->with('success', 'Rekod dikemaskini.');
    }

    public function updateRecord(Request $request, $id)
    {
        $record = AssetMaintenance::with('asset')->findOrFail($id);
        
        $request->validate([
            'jenis_kerja' => 'required|string',
            'kos_rm' => 'required|numeric',
            'tarikh' => 'required|date',
            'status' => 'required|in:Siap,Dalam Proses',
            'next_cal' => 'nullable|date',
        ]);

        $record->update($request->only('jenis_kerja', 'kos_rm', 'tarikh', 'status') + [
        'updated_by' => auth()->id()
    ]);

        if ($record->asset->category === 'Weighbridge' && $request->has('next_cal')) {
            $asset = $record->asset;
            $metadata = $asset->metadata ?? [];
            $metadata['tarikh_kalibrasi_seterusnya'] = $request->next_cal;
            
            $asset->metadata = $metadata;
            $asset->save();
        }

        return back()->with('success', 'Rekod dikemaskini.');
    }

    public function destroyRecord($id)
    {
        AssetMaintenance::findOrFail($id)->delete();
        return back()->with('success', 'Rekod berjaya dipadam.');
    }
}