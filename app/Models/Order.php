<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'payment_method',
        'total_amount',
        'shipping_address',
        'city_id',
        'is_gift',
        'receiver_name',
        'receiver_phone',
        'location_description',
        'extra_notes',
        'is_anonymous_delivery',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'is_gift' => 'boolean',
            'is_anonymous_delivery' => 'boolean',
        ];
    }

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the city for this order.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get all items for this order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get all payments for this order.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get all discounts applied to this order.
     */
    public function discounts(): HasMany
    {
        return $this->hasMany(OrderDiscount::class);
    }

    /**
     * Scope a query to only include gift orders.
     */
    public function scopeGifts($query)
    {
        return $query->where('is_gift', true);
    }

    /**
     * Scope a query by status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
