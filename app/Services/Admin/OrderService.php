<?php

namespace App\Services\Admin;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class OrderService
{
    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        try {
            $q = Order::query()
                ->with(['user:id,name,email', 'city:id,name_en,name_ar', 'deliveryTimeSlot:id,window_start,window_end', 'items.product:id,name_en,name_ar'])
                ->latest();

            if (!empty($filters['status'])) {
                $q->where('status', $filters['status']);
            }
            if (!empty($filters['delivery_status'])) {
                $q->where('delivery_status', $filters['delivery_status']);
            }
            if (!empty($filters['search'])) {
                $s = trim($filters['search']);
                $q->where(function ($qq) use ($s) {
                    $qq->where('id', $s)
                       ->orWhere('receiver_name', 'like', "%{$s}%")
                       ->orWhere('receiver_phone', 'like', "%{$s}%")
                       ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%"));
                });
            }

            return $q->paginate($perPage)->withQueryString();
        } catch (Throwable $e) {
            Log::error('OrderService@list failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function find(int $id): Order
    {
        try {
            return Order::with([
                'user:id,name,email',
                'city:id,name_en,name_ar',
                'deliveryTimeSlot:id,window_start,window_end,crosses_midnight',
                'items.product:id,name_en,name_ar,slug',
                'payments' => fn($q) => $q->latest(),
                'discounts',
            ])->findOrFail($id);
        } catch (Throwable $e) {
            Log::error('OrderService@find failed', ['order_id' => $id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateDeliveryStatus(int $orderId, string $deliveryStatus): Order
    {
        try {
            return DB::transaction(function () use ($orderId, $deliveryStatus) {
                /** @var Order $order */
                $order = Order::findOrFail($orderId);
                $order->delivery_status = $deliveryStatus;
                // Optional: sync a higher-level status when delivered/cancelled
                if ($deliveryStatus === 'delivered') {
                    $order->status = 'completed';
                } elseif ($deliveryStatus === 'cancelled') {
                    $order->status = 'cancelled';
                }
                $order->save();

                return $this->find($order->id);
            });
        } catch (Throwable $e) {
            Log::error('OrderService@updateDeliveryStatus failed', [
                'order_id' => $orderId,
                'delivery_status' => $deliveryStatus,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function invoiceData(int $orderId): Order
    {
        // Same as find() but kept separate for clarity/extension (e.g., tax, totals).
        return $this->find($orderId);
    }

    /** Quick helpers for UI */
    public function deliveryStatusOptions(): array
    {
        return ['pending', 'processing', 'out_for_delivery', 'delivered', 'cancelled'];
    }

    public function paymentMethodLabel(?string $method): string
    {
        return match ($method) {
            'cod' => 'Cash on Delivery',
            'card' => 'Credit/Debit Card',
            'wallet' => 'Wallet',
            default => 'Unknown',
        };
    }
}
