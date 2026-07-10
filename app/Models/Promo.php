<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_type',
        'discount_value',
        'min_transaction',
        'max_discount',
        'valid_from',
        'valid_until',
        'usage_limit',
        'used_count',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_transaction' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'promo_code', 'code');
    }

    // Helper Methods
    public function isValid(): bool
    {
        return $this->is_active 
            && $this->valid_from->lte(now()) 
            && $this->valid_until->gte(now())
            && ($this->usage_limit == 0 || $this->used_count < $this->usage_limit);
    }

    public function calculateDiscount(float|int $totalAmount): float
    {
        if (!$this->isValid()) {
            return 0;
        }

        if ($totalAmount < $this->min_transaction) {
            return 0;
        }

        $discount = $this->discount_type === 'percentage'
            ? ($totalAmount * $this->discount_value / 100)
            : (float) $this->discount_value;

        if ($this->max_discount > 0 && $discount > $this->max_discount) {
            $discount = (float) $this->max_discount;
        }

        return $discount;
    }

    public function getStatusBadgeAttribute(): array
    {
        if (!$this->is_active) {
            return ['class' => 'bg-slate-100 text-slate-700', 'label' => 'Inactive'];
        }

        if ($this->usage_limit > 0 && $this->used_count >= $this->usage_limit) {
            return ['class' => 'bg-red-100 text-red-700', 'label' => 'Used Out'];
        }

        if ($this->valid_until->lt(now())) {
            return ['class' => 'bg-red-100 text-red-700', 'label' => 'Expired'];
        }

        return ['class' => 'bg-green-100 text-green-700', 'label' => 'Active'];
    }
}