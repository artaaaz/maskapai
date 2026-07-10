<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'full_name',
        'gender',
        'birth_date',
        'passport_number',
        'seat_number',
        'travel_class',
        'title',
        'phone',
        'email',
        'frequent_flyer_number',
        'has_checked_in',
        'has_boarded',
        'checked_in_at',
        'boarded_at',
        'checked_out_at',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function getCheckInStatusAttribute()
    {
        if ($this->has_boarded) return ['class' => 'bg-green-100 text-green-700', 'label' => 'Boarded'];
        if ($this->has_checked_in) return ['class' => 'bg-blue-100 text-blue-700', 'label' => 'Checked In'];
        return ['class' => 'bg-slate-100 text-slate-500', 'label' => 'Not Checked In'];
    }

    // Helper Methods
    public function getFullNameWithTitleAttribute()
    {
        $titleMap = [
            'Mr' => 'Tn.',
            'Mrs' => 'Ny.',
            'Ms' => 'Nn.',
        ];

        $title = $this->title ? ($titleMap[$this->title] ?? $this->title) . ' ' : '';
        return $title . $this->full_name;
    }
}