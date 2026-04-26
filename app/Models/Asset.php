<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'pts_lokasi',
        'metadata', // This stores extra fields like next_calibration_date
    ];

    /**
     * The attributes that should be cast.
     * This converts the JSON from the database into a PHP array automatically.
     */
    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get all maintenance logs for this specific asset.
     */
    public function maintenances(): HasMany
    {
        return $this->hasMany(AssetMaintenance::class, 'asset_id');
    }

    /**
     * Scope a query to only include assets of a given category.
     * Usage: Asset::category('Genset')->get();
     */
    public function scopeCategory($query, $type)
    {
        return $query->where('category', $type);
    }
}