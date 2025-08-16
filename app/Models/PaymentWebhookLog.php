<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentWebhookLog extends Model
{
    protected $fillable = [
        'payment_id',
        'event_id',
        'event_type',
        'signature_ok',
        'payload',
        'received_at',
    ];

    protected function casts(): array
    {
        return [
            'signature_ok' => 'boolean',
            'payload' => 'array',
            'received_at' => 'datetime',
        ];
    }

    /**
     * Get the payment that owns the webhook log.
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Scope a query to only include valid signatures.
     */
    public function scopeValidSignature($query)
    {
        return $query->where('signature_ok', true);
    }
}
