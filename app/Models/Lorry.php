<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lorry extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_plat', 
        'model', 
        'tahun', 
        'pts_lokasi', 
        'odometer_semasa'
    ];

   
    public function maintenanceLogs()
    {
        return $this->hasMany(MaintenanceLog::class);
    }
} 