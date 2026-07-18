<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = [
        'code',
        'name',
        'title',
        'description',
        'discount_type',
        'discount_value',
        'max_discount',
        'min_transaction',
        'min_purchase',
        'quota',
        'used_count',
        'usage_limit',
        'valid_from',
        'valid_until',
        'is_active',
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
        'discount_value' => 'decimal:0',
        'max_discount' => 'decimal:0',
        'min_transaction' => 'decimal:0',
        'min_purchase' => 'decimal:0',
        'used_count' => 'integer',
        'quota' => 'integer',
        'usage_limit' => 'integer',
    ];

    public function usages()
    {
        return $this->hasMany(PromoUsage::class);
    }

    public function getUsageCountAttribute()
    {
        return $this->usages()->count();
    }

    public function getTotalDiscountAttribute()
    {
        return $this->usages()->sum('discount_amount');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->where(function ($q) {
                $q->whereNull('quota')
                  ->orWhere('quota', 0)
                  ->orWhereColumn('used_count', '<', 'quota');
            });
    }

    public function isValid(): bool
    {
        return $this->is_active
            && $this->valid_from <= now()
            && $this->valid_until >= now()
            && ($this->quota === null || $this->quota === 0 || $this->used_count < $this->quota)
            && ($this->usage_limit === null || $this->usage_limit === 0 || $this->used_count < $this->usage_limit);
    }

    public function calculateDiscount($subtotal): float
    {
        if (!$this->isValid()) return 0;
        if ($subtotal < $this->min_purchase) return 0;

        if ($this->discount_type === 'percentage') {
            $discount = $subtotal * ($this->discount_value / 100);
            if ($this->max_discount > 0 && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
            return $discount;
        }

        return min($this->discount_value, $subtotal);
    }

    public function getStatusBadgeAttribute(): array
    {
        if ($this->valid_until && now()->greaterThan($this->valid_until)) {
            return [
                'label' => 'Expired',
                'class' => 'bg-red-100 text-red-700',
            ];
        }

        if ($this->valid_from && now()->lessThan($this->valid_from)) {
            return [
                'label' => 'Akan Datang',
                'class' => 'bg-blue-100 text-blue-700',
            ];
        }

        if ($this->quota > 0 && $this->used_count >= $this->quota) {
            return [
                'label' => 'Kuota Habis',
                'class' => 'bg-orange-100 text-orange-700',
            ];
        }

        if (!$this->is_active) {
            return [
                'label' => 'Nonaktif',
                'class' => 'bg-gray-100 text-gray-700',
            ];
        }

        return [
            'label' => 'Aktif',
            'class' => 'bg-green-100 text-green-700',
        ];
    }

    public function getFormattedDiscountAttribute(): string
    {
        if ($this->discount_type === 'percentage') {
            $text = $this->discount_value . '%';
            if ($this->max_discount > 0) {
                $text .= ' (maks. Rp' . number_format($this->max_discount, 0, ',', '.') . ')';
            }
            return $text;
        }
        return 'Rp' . number_format($this->discount_value, 0, ',', '.');
    }

    public function isExpired(): bool
    {
        return $this->valid_until && now()->greaterThan($this->valid_until);
    }

    public function isActive(): bool
    {
        return $this->isValid();
    }

    public function getRemainingQuotaAttribute(): int
    {
        if ($this->quota === null || $this->quota === 0) {
            return -1;
        }
        return max(0, $this->quota - $this->used_count);
    }
}