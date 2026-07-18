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

    // Relationships - New
    public function flightClasses()
    {
        return $this->hasMany(FlightClass::class);
    }

    public function seatReservations()
    {
        return $this->hasMany(SeatReservation::class);
    }

    // Relationships - Existing
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

    public function seats()
    {
        return $this->hasManyThrough(Seat::class, Airplane::class, 'id', 'airplane_id', 'airplane_id', 'id');
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

    /**
     * Hitung total kursi pesawat dari tabel seats
     */
    public function getTotalSeatsCountAttribute(): int
    {
        return $this->airplane?->seats()->count() ?? 0;
    }

    /**
     * Hitung kursi yang sudah dibooking untuk flight ini
     * Menggunakan data dari tabel seats (status = booked/pending) dan passenger (seat_number terisi)
     */
    public function getBookedSeatsCountAttribute(): int
    {
        // Prioritaskan dari tabel seats (status booked/unavailable)
        $bookedFromSeats = $this->airplane?->seats()
            ->whereIn('status', ['booked', 'unavailable'])
            ->count() ?? 0;

        // Fallback: hitung dari passenger yang punya seat_number dan booking tidak cancelled
        $bookedFromPassengers = Passenger::whereHas('booking', function ($q) {
            $q->where('flight_id', $this->id)
              ->where('status', '!=', 'cancelled');
        })->whereNotNull('seat_number')->count();

        return max($bookedFromSeats, $bookedFromPassengers);
    }

    /**
     * Hitung kursi tersedia berdasarkan total kursi pesawat dikurangi booking real.
     */
    public function getAvailableSeatsCountAttribute(): int
    {
        $totalSeats = $this->airplane?->seats()->count() ?? 0;
        
        if ($totalSeats === 0) {
            return max(0, (int) $this->available_seats);
        }
        
        $bookedSeats = $this->booked_seats_count;
        return max(0, $totalSeats - $bookedSeats);
    }

    public function isAvailable(): bool
    {
        return $this->available_seats_count > 0 && $this->departure_time->isFuture();
    }

    /**
     * BUG 1 FIX: root cause Flight lama tidak muncul di Customer.
     *
     * HomeController & FlightResultsController mensyaratkan whereHas('flightClasses')
     * supaya harga bisa ditampilkan. Flight BARU otomatis dapat baris flight_classes
     * (dibuat oleh Admin\FlightController@store), tapi Flight LAMA yang sudah ada di
     * database sebelum fitur flight_classes ada (atau data yang dipindah/diimpor ke
     * server baru setelah migration backfill sempat jalan) tidak punya baris
     * flight_classes sama sekali -> whereHas() otomatis MEMBUANG flight tsb dari hasil
     * query walau datanya valid dan available_seats > 0.
     *
     * Method ini memperbaiki data yang hilang tsb secara aman: kalau Flight belum
     * punya flightClasses, buatkan satu baris "economy" dari kolom price &
     * available_seats milik Flight itu sendiri (kolom yang memang sudah ada sejak
     * awal). Idempotent & tidak mengubah struktur tabel manapun - hanya mengisi baris
     * yang seharusnya sudah ada.
     */
    public function ensureHasFlightClass(): void
    {
        if ($this->relationLoaded('flightClasses')) {
            if ($this->flightClasses->isNotEmpty()) {
                return;
            }
        } elseif ($this->flightClasses()->exists()) {
            return;
        }

        $this->flightClasses()->create([
            'class_name' => 'economy',
            'price' => $this->price,
            'seat_quota' => $this->available_seats,
        ]);

        $this->unsetRelation('flightClasses');
        $this->load('flightClasses');
    }
}