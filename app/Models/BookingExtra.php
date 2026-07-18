<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingExtra extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'extra_type',
        'extra_name',
        'extra_price',
        'quantity',
    ];

    protected $casts = [
        'extra_price' => 'decimal:2',
    ];

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Helper Methods
    public function getTotalPriceAttribute(): float
    {
        return (float) $this->extra_price * (int) $this->quantity;
    }

    public function getIconAttribute(): string
    {
        return match($this->extra_type) {
            'seat' => '💺',
            'baggage' => '🧳',
            'meal' => '🍱',
            default => '📦',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->extra_type) {
            'seat' => 'Seat Selection',
            'baggage' => 'Extra Baggage',
            'meal' => 'In-flight Meal',
            default => 'Extra Service',
        };
    }
}