<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'coverage_amount',
        'price',
        'type',
        'is_active',
    ];

    protected $casts = [
        'coverage_amount' => 'decimal:2',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Helper Methods
    public function getIconAttribute(): string
    {
        return match($this->type) {
            'baggage' => '🧳',
            'delay' => '⏰',
            'disruption' => '✈️',
            'bundle' => '🛡️',
            default => '🛡️',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'baggage' => 'Asuransi Bagasi',
            'delay' => 'Proteksi Keterlambatan',
            'disruption' => 'Proteksi Gangguan',
            'bundle' => 'Bundle Perlindungan',
            default => 'Asuransi',
        };
    }

    public function getStatusBadgeAttribute(): array
    {
        return $this->is_active 
            ? ['class' => 'bg-green-100 text-green-700', 'label' => 'Available']
            : ['class' => 'bg-slate-100 text-slate-700', 'label' => 'Unavailable'];
    }
}