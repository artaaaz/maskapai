<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airline extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'logo',
        'photos',
    ];

    // Relationships
    public function flights()
    {
        return $this->hasMany(Flight::class);
    }

    public function airplanes()
    {
        return $this->hasMany(Airplane::class);
    }

    // Helper Methods
    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }

    public function getInitialsAttribute()
    {
        return strtoupper(substr($this->name, 0, 2));
    }
}