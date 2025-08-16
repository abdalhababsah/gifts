<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDiscount extends Model
{
    protected $fillable = [
        'order_id',
        'discount_code_id',
        'applied_value',
    ];

    protected function casts(): array
    {
        return [
            'applied_value' => 'decimal:2',
        ];
    }

    /**
     * Get the order that owns the discount.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the discount code that was applied.
     */
    public function discountCode(): BelongsTo
    {
        return $this->belongsTo(DiscountCode::class);
    }
}
