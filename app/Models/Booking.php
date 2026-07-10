<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'flight_id',
        'booking_code',
        'total_passengers',
        'total_price',
        'status',
        'trip_type',
        'travel_class',
        'return_date',
        'return_flight_id',
        'promo_code',
        'discount_amount',
        'convenience_fee',
        'tax_amount',
        'points_earned',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'convenience_fee' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'trip_type' => 'string',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function returnFlight()
    {
        return $this->belongsTo(Flight::class, 'return_flight_id');
    }
    public function passengers()
    {
        return $this->hasMany(Passenger::class);
    }

    public function getSeatsAttribute()
    {
        return $this->passengers()->whereNotNull('seat_number')->get();
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function extras()
    {
        return $this->hasMany(BookingExtra::class);
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class, 'promo_code', 'code');
    }

    // Helper Methods
    public function getFinalPriceAttribute()
    {
        return $this->total_price - $this->discount_amount + $this->convenience_fee + $this->tax_amount;
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'confirmed' => ['class' => 'bg-green-100 text-green-700', 'label' => 'Confirmed'],
            'pending' => ['class' => 'bg-amber-100 text-amber-700', 'label' => 'Pending'],
            'cancelled' => ['class' => 'bg-red-100 text-red-700', 'label' => 'Cancelled'],
            default => ['class' => 'bg-slate-100 text-slate-700', 'label' => 'Unknown'],
        };
    }

    public function getPaymentUrlAttribute()
    {
        return route('customer.payment.show', $this);
    }

    public function getRemainingTimeAttribute()
    {
        if ($this->payment && $this->payment->expired_at) {
            return now()->diffInHours($this->payment->expired_at, false);
        }
        return 24;
    }
}
