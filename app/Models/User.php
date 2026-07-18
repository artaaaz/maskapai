<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Ditambahkan untuk RBAC (Admin, Staff, Customer, Manager)
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Tambahkan relationships ini di dalam class User

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function seatReservations()
    {
        return $this->hasMany(SeatReservation::class);
    }

    public function promoUsages()
    {
        return $this->hasMany(PromoUsage::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function isManager()
    {
        return $this->role === 'manager';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    public function getRoleBadgeAttribute()
    {
        return match ($this->role) {
            'admin' => ['class' => 'bg-red-100 text-red-700', 'label' => 'Admin'],
            'staff' => ['class' => 'bg-blue-100 text-blue-700', 'label' => 'Staff'],
            'manager' => ['class' => 'bg-purple-100 text-purple-700', 'label' => 'Manager'],
            'customer' => ['class' => 'bg-green-100 text-green-700', 'label' => 'Customer'],
            default => ['class' => 'bg-slate-100 text-slate-700', 'label' => 'Unknown'],
        };
    }
}