<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'country',
        'iata_code',
        'terminal',
        'timezone',
    ];

    // Relationships
    public function departureFlights()
    {
        return $this->hasMany(Flight::class, 'departure_airport_id');
    }

    public function arrivalFlights()
    {
        return $this->hasMany(Flight::class, 'arrival_airport_id');
    }

    // Helper Methods
    public function getFullNameAttribute()
    {
        return $this->name . ' (' . $this->iata_code . ')';
    }

    public function getCityWithCodeAttribute()
    {
        return $this->city . ' (' . $this->iata_code . ')';
    }
}