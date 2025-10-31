<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryTimeSlotResource extends JsonResource
{
    public function toArray($request): array
    {
        $locale = app()->getLocale();
        $nameField = 'name_' . ($locale === 'ar' ? 'ar' : 'en');

        // Your table has window_start/window_end; expose as start_time/end_time in API.
        $start = $this->start_time ?? $this->window_start;
        $end   = $this->end_time   ?? $this->window_end;

        return [
            'id'         => $this->id,
            'name'       => $this->{$nameField},
            'start_time' => optional($start ? \Carbon\Carbon::parse($start) : null)->toDayDateTimeString(),
            'end_time'   => optional($end ? \Carbon\Carbon::parse($end) : null)->toDayDateTimeString(),
        ];
    }
}
