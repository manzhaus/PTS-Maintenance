<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetRequest extends Model
{
    use HasFactory;

    /**
     * Nama table (optional jika nama model dah ikut standard plural)
     */
    protected $table = 'budget_requests';

    /**
     * Kolum yang dibenarkan untuk mass assignment.
     * Penting untuk fungsi BudgetRequest::create() nanti.
     */
    protected $fillable = [
        'pts_lokasi',
        'jumlah_dipohon',
        'sebab',
        'lampiran_quotation',
        'status', // Draft, Submitted, Approved, Rejected
        'user_id', // ID Supervisor yang buat request
    ];

    /**
     * Relationship: Setiap permohonan bajet milik seorang User (Supervisor).
     * Memudahkan Admin tengok siapa yang hantar request.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Helper Function: Untuk warna status pada UI
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'Approved'  => 'badge-success',
            'Rejected'  => 'badge-danger',
            'Submitted' => 'badge-primary',
            default     => 'badge-secondary',
        };
    }
}