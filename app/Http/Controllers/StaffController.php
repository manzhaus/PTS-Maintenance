<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StaffController extends Controller
{
    public function index()
    {
        // Fetch all staff from MySQL
        $staff = Staff::all();

        // Render the Vue page and pass the staff data
        return Inertia::render('Staff/Index', [
            'staff' => $staff
        ]);
    }

    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jawatan' => 'required|string',
            'gaji_asas' => 'required|numeric',
            'pts_lokasi' => 'required|string',
            'tarikh_mula_kerja' => 'required|date',
        ]);

        // Save to Database
        Staff::create($validated);

        return redirect()->back()->with('success', 'Staff berjaya didaftarkan!');
    }
}
