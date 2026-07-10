<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'flight_id',
        'seat_number',
        'class',
        'position',
        'status',
        'booking_id',
    ];

    // Relationships
    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Helper Methods
    public function getClassLabelAttribute()
    {
        return match($this->class) {
            'economy' => 'Economy',
            'premium_economy' => 'Premium Economy',
            'business' => 'Business',
            'first' => 'First Class',
            default => 'Unknown',
        };
    }

    public function getClassBadgeAttribute()
    {
        return match($this->class) {
            'economy' => ['class' => 'bg-blue-100 text-blue-700', 'label' => 'Economy'],
            'premium_economy' => ['class' => 'bg-indigo-100 text-indigo-700', 'label' => 'Premium Economy'],
            'business' => ['class' => 'bg-purple-100 text-purple-700', 'label' => 'Business'],
            'first' => ['class' => 'bg-amber-100 text-amber-700', 'label' => 'First'],
            default => ['class' => 'bg-slate-100 text-slate-700', 'label' => 'Unknown'],
        };
    }
}