<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceLog extends Model
{
    protected $fillable = [
        'lorry_id',
        'created_by',
        'updated_by',
        'tarikh',
        'jenis_maintenance',
        'kos_rm',
        'vendor',
        'resit_upload',
        'odometer_masa_servis'
    ];

    // Relationship to see who logged this
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relationship to the Lorry
    public function lorry(): BelongsTo
    {
        return $this->belongsTo(Lorry::class);
    }
}
