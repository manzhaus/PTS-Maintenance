<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetMaintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'jenis_kerja',
        'kos_rm',
        'tarikh',
        'resit_path',
        'status',
        'created_by',
    ];

    /**
     * Get the asset that owns the maintenance record.
     */
    public function asset()
{
    return $this->belongsTo(Asset::class, 'asset_id');
}

    /**
     * Get the user (Supervisor/Admin) who created the record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}