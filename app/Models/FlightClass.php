<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'flight_id',
        'class_name',
        'price',
        'seat_quota',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function seatReservations()
    {
        return $this->hasMany(SeatReservation::class);
    }

    public function getBookedCountAttribute()
    {
        return $this->seatReservations()
            ->whereIn('status', ['paid', 'checked_in', 'boarded', 'completed'])
            ->count();
    }

    public function getAvailableQuotaAttribute()
    {
        return max(0, $this->seat_quota - $this->booked_count);
    }
}