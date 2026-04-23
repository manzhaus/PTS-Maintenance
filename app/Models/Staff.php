<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    // Add this protected property
    protected $fillable = [
        'nama',
        'jawatan',
        'gaji_asas',
        'pts_lokasi',
        'tarikh_mula_kerja',
    ];
}
