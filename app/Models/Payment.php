<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'payment_method',
        'amount',
        'payment_status',
        'transaction_code',
        'midtrans_transaction_id',
        'payment_gateway',
        'virtual_account_number',
        'payment_proof',
        'paid_at',
        'expired_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Helper Methods
    public function getStatusBadgeAttribute()
    {
        return match($this->payment_status) {
            'paid' => ['class' => 'bg-green-100 text-green-700', 'label' => 'Paid'],
            'pending' => ['class' => 'bg-amber-100 text-amber-700', 'label' => 'Pending'],
            'failed' => ['class' => 'bg-red-100 text-red-700', 'label' => 'Failed'],
            default => ['class' => 'bg-slate-100 text-slate-700', 'label' => 'Unknown'],
        };
    }

    public function isExpired()
    {
        return $this->expired_at && now()->greaterThan($this->expired_at);
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }
}