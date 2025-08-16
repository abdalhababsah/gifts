<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DiscountCode extends Model
{
    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'min_order_total',
        'usage_limit',
        'per_user_limit',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'min_order_total' => 'decimal:2',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get all order discounts that used this discount code.
     */
    public function orderDiscounts(): HasMany
    {
        return $this->hasMany(OrderDiscount::class);
    }

    /**
     * Scope a query to only include active discount codes.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include valid discount codes (within date range).
     */
    public function scopeValid($query)
    {
        $now = now();
        return $query->where('is_active', true)
                    ->where(function ($query) use ($now) {
                        $query->where('start_date', '<=', $now)
                              ->orWhereNull('start_date');
                    })
                    ->where(function ($query) use ($now) {
                        $query->where('end_date', '>=', $now)
                              ->orWhereNull('end_date');
                    });
    }

    /**
     * Check if the discount code is percentage type.
     */
    public function isPercentage(): bool
    {
        return $this->type === 'percent';
    }

    /**
     * Check if the discount code is fixed amount type.
     */
    public function isFixed(): bool
    {
        return $this->type === 'fixed';
    }

    /**
     * Calculate discount amount for a given order total.
     */
    public function calculateDiscount(float $orderTotal): float
    {
        if ($orderTotal < (float) $this->min_order_total) {
            return 0;
        }

        if ($this->isPercentage()) {
            return ($orderTotal * (float) $this->value) / 100;
        }

        return (float) $this->value;
    }
}
