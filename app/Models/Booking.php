<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    // Status Constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_CHECKED_IN = 'checked_in';
    public const STATUS_BOARDED = 'boarded';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($booking) {
            if ($booking->isDirty('status')) {
                $oldStatus = $booking->getOriginal('status');
                $newStatus = $booking->status;

                if (!self::isValidTransition($oldStatus, $newStatus)) {
                    \Illuminate\Support\Facades\Log::warning("Invalid booking status transition attempted: {$oldStatus} -> {$newStatus} for Booking ID: {$booking->id}");
                    $booking->status = $oldStatus;
                }
            }
        });
    }

    public static function isValidTransition(string $oldStatus, string $newStatus): bool
    {
        if ($oldStatus === $newStatus) {
            return true;
        }

        if (in_array($oldStatus, ['cancelled', 'completed'])) {
            return false;
        }

        $ranks = [
            'pending' => 0,
            'paid' => 1,
            'confirmed' => 2,
            'checked_in' => 3,
            'boarded' => 4,
            'in_progress' => 5,
            'completed' => 6,
        ];

        if ($newStatus === 'cancelled') {
            return in_array($oldStatus, ['pending', 'confirmed']);
        }

        if (!isset($ranks[$oldStatus]) || !isset($ranks[$newStatus])) {
            return false;
        }

        return $ranks[$newStatus] > $ranks[$oldStatus];
    }

    protected $fillable = [
        'user_id',
        'flight_id',
        'flight_class_id',
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

    // Relationships - New
    public function flightClass()
    {
        return $this->belongsTo(FlightClass::class);
    }

    public function seatReservations()
    {
        return $this->hasMany(SeatReservation::class);
    }

    public function promoUsage()
    {
        return $this->hasOne(PromoUsage::class);
    }

    public function checkIn()
    {
        return $this->hasOne(CheckIn::class);
    }

    public function boarding()
    {
        return $this->hasOne(Boarding::class);
    }

    public function flightCheckout()
    {
        return $this->hasOne(FlightCheckout::class);
    }

    // Relationships - Existing
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
    /**
     * Hitung Grand Total untuk pembayaran
     * Grand Total = total_price (harga tiket + convenience fee)
     * PPN sudah termasuk dalam harga tiket.
     * Insurance & Promo dinonaktifkan sementara.
     */
    public function getGrandTotalAttribute()
    {
        return max(0, $this->total_price - ($this->discount_amount ?? 0));
    }

    public function getFinalPriceAttribute()
    {
        // Alias untuk grand_total - konsisten
        return $this->grand_total;
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'pending' => ['class' => 'bg-slate-100 text-slate-600', 'label' => 'Waiting Payment'],
            'paid' => ['class' => 'bg-blue-100 text-blue-700', 'label' => 'Paid'],
            'confirmed' => ['class' => 'bg-blue-100 text-blue-700', 'label' => 'Confirmed'],
            'checked_in' => ['class' => 'bg-blue-100 text-blue-700', 'label' => 'Checked In'],
            'boarded' => ['class' => 'bg-amber-100 text-amber-700', 'label' => 'Boarded'],
            'in_progress' => ['class' => 'bg-amber-100 text-amber-700', 'label' => 'In Progress'],
            'completed' => ['class' => 'bg-green-100 text-green-700', 'label' => 'Completed'],
            'cancelled' => ['class' => 'bg-red-100 text-red-700', 'label' => 'Cancelled'],
            default => ['class' => 'bg-slate-100 text-slate-500', 'label' => 'Unknown'],
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

    /**
     * Determine human-friendly flight status/countdown for UI.
     * Returns array with keys: text, class (css bg class).
     */
    public function getFlightStatusAttribute(): array
    {
        $now = now();
        $flight = $this->flight;

        $arrival = $flight?->arrival_time;
        $departure = $flight?->departure_time;

        // 1) If booking completed, or any passenger completed, or now past arrival -> finished
        $hasCompletedPassenger = $this->passengers()->where('status', 'completed')->exists();
        if ($this->status === 'completed' || $hasCompletedPassenger || ($arrival && $now->greaterThan($arrival))) {
            return ['text' => 'Penerbangan Selesai', 'class' => 'bg-slate-500'];
        }

        // 2) If any passenger boarded -> in flight
        $hasBoarded = $this->passengers()->where('status', 'boarded')->exists();
        if ($hasBoarded) {
            return ['text' => 'Dalam Penerbangan', 'class' => 'bg-amber-500'];
        }

        // 3) If any passenger checked_in -> ready to board
        $hasCheckedIn = $this->passengers()->where('status', 'checked_in')->exists();
        if ($hasCheckedIn) {
            return ['text' => 'Siap Boarding', 'class' => 'bg-amber-500'];
        }

        // 4) If departure in future -> countdown
        if ($departure && $departure->isFuture()) {
            $diffInDays = (int) $now->diffInDays($departure, false);
            $diffInHours = (int) $now->diffInHours($departure, false);
            $diffInMinutes = (int) $now->diffInMinutes($departure, false);
            $remainingHours = $diffInHours % 24;

            if ($diffInDays > 0) {
                $text = $diffInDays . ' hari ' . $remainingHours . ' jam lagi';
                $badge = $diffInDays <= 1 ? 'bg-red-500' : ($diffInDays <= 3 ? 'bg-amber-500' : 'bg-green-500');
            } elseif ($diffInHours > 0) {
                if ($diffInMinutes % 60 > 0) {
                    $text = $diffInHours . ' jam ' . ($diffInMinutes % 60) . ' menit lagi';
                } else {
                    $text = $diffInHours . ' jam lagi';
                }
                $badge = $diffInHours <= 1 ? 'bg-red-500' : 'bg-amber-500';
            } elseif ($diffInMinutes > 0) {
                $text = $diffInMinutes . ' menit lagi';
                $badge = 'bg-red-500';
            } else {
                $text = 'Penerbangan dalam beberapa menit';
                $badge = 'bg-red-500';
            }

            return ['text' => $text, 'class' => $badge];
        }

        // Default fallback -> finished
        return ['text' => 'Penerbangan Selesai', 'class' => 'bg-slate-500'];
    }
}
