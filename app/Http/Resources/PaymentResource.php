<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->id,
            'status'          => $this->status,
            'amount'          => (float) $this->amount,
            'currency'        => $this->currency,
            'provider'        => $this->provider,
            'provider_txn_id' => $this->provider_txn_id,
            'paid_at'         => optional($this->paid_at ? \Carbon\Carbon::parse($this->paid_at) : null)->toDayDateTimeString(),
        ];
    }
}
