<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;

    protected $fillable = [
        'airline_id',
        'airplane_id',
        'departure_airport_id',
        'arrival_airport_id',
        'flight_number',
        'departure_time',
        'arrival_time',
        'price',
        'available_seats',
        'duration_minutes',
        'baggage_allowance_kg',
        'refund_policy',
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
        'price' => 'decimal:2',
    ];

    // Relationships
    public function airline()
    {
        return $this->belongsTo(Airline::class);
    }

    public function airplane()
    {
        return $this->belongsTo(Airplane::class);
    }

    public function departureAirport()
    {
        return $this->belongsTo(Airport::class, 'departure_airport_id');
    }

    public function arrivalAirport()
    {
        return $this->belongsTo(Airport::class, 'arrival_airport_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Helper Methods
    public function getDurationFormattedAttribute()
    {
        if (!$this->duration_minutes) {
            $minutes = $this->departure_time->diffInMinutes($this->arrival_time);
            $hours = floor($minutes / 60);
            $mins = $minutes % 60;
            return $hours . 'j ' . $mins . 'm';
        }

        $hours = floor($this->duration_minutes / 60);
        $mins = $this->duration_minutes % 60;
        return $hours . 'j ' . $mins . 'm';
    }

    public function getRouteAttribute()
    {
        $departure = $this->departureAirport?->iata_code ?? '???';
        $arrival = $this->arrivalAirport?->iata_code ?? '???';
        return $departure . ' → ' . $arrival;
    }

    public function isAvailable()
    {
        return $this->available_seats > 0 && $this->departure_time->isFuture();
    }
}