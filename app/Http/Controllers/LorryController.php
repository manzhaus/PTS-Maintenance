<?php

namespace App\Http\Controllers;

use App\Models\Lorry;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LorryController extends Controller
{
    public function index()
{
    $user = auth()->user();

    $query = Lorry::withCount(['maintenanceLogs' => function ($query) {
        $query->where('tarikh', '>=', now()->subDays(30));
    }]);

    // Jika BUKAN admin, tambah syarat tapis lokasi
    if ($user->role !== 'admin') {
        $query->where('pts_lokasi', $user->pts_lokasi);
    }

    $lorries = $query->get();

    return Inertia::render('Lorries/Index', [
        'lorries' => $lorries
    ]);
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_plat' => 'required|string|unique:lorries,no_plat',
            'model' => 'required|string',
            'tahun' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'pts_lokasi' => 'required|string',
            'odometer_semasa' => 'required|integer',
        ]);

        Lorry::create($validated);

        return redirect()->back()->with('message', 'Lori berjaya didaftarkan!');
    }
}
