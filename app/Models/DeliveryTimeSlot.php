<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryTimeSlot extends Model
{
    protected $fillable = [
        'code',
        'name_en',
        'name_ar',
        'window_start',
        'window_end',
        'crosses_midnight',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'window_start' => 'datetime:H:i',
            'window_end' => 'datetime:H:i',
            'crosses_midnight' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get all orders for this delivery time slot.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Scope a query to only include active time slots.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order by sort_order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get the formatted time window string.
     */
    public function getTimeWindowAttribute(): string
    {
        return $this->window_start->format('H:i') . ' - ' . $this->window_end->format('H:i');
    }
}
