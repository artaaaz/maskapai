<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightCheckout extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'staff_id',
        'checkout_at',
    ];

    protected $casts = [
        'checkout_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}