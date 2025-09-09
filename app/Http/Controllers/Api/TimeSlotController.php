<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Models\DeliveryTimeSlot;
use Illuminate\Http\JsonResponse;

class TimeSlotController extends ApiController
{
    public function index(): JsonResponse
    {
        $locale = app()->getLocale();

        $slots = DeliveryTimeSlot::active()
            ->ordered()
            ->get(['id','code','name_en','name_ar','window_start','window_end','crosses_midnight','sort_order']);

        return response()->json([
            'success' => true,
            'data' => $slots->map(fn($t) => [
                'id' => $t->id,
                'code' => $t->code,
                'name' => $locale === 'ar' ? $t->name_ar : $t->name_en,
                'window_start' => $t->window_start,
                'window_end' => $t->window_end,
                'crosses_midnight' => (bool)$t->crosses_midnight,
                'sort_order' => (int)$t->sort_order,
            ]),
        ]);
    }
}
