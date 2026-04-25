<?php

namespace App\Http\Controllers;

use App\Models\Lorry;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LorryController extends Controller
{
    public function index()
{
    $lorries = Lorry::withCount(['maintenanceLogs' => function ($query) {
        $query->where('tarikh', '>=', now()->subDays(30));
    }])->get();

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
