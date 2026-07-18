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
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'has_checked_in' => 'boolean',
        'has_boarded' => 'boolean',
        'checked_in_at' => 'datetime',
        'boarded_at' => 'datetime',
        'checked_out_at' => 'datetime',
    ];

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Status badge untuk menampilkan status passenger di UI.
     * SELALU mengembalikan array, TIDAK PERNAH null.
     */
    public function getCheckInStatusAttribute(): array
    {
        return match ($this->status) {
            'checked_in' => ['class' => 'bg-blue-100 text-blue-700', 'label' => 'Checked In'],
            'boarded' => ['class' => 'bg-amber-100 text-amber-700', 'label' => 'Boarded'],
            'completed' => ['class' => 'bg-green-100 text-green-700', 'label' => 'Completed'],
            'no_show' => ['class' => 'bg-red-100 text-red-700', 'label' => 'No Show'],
            default => ['class' => 'bg-slate-100 text-slate-500', 'label' => 'Waiting'],
        };
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