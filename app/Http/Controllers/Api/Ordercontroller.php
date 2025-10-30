<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends ApiController
{
    /**
     * Get user's orders with pagination - only orders with succeeded payments
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $orders = Order::where('user_id', $user->id)
            ->whereHas('payment', function($query) {
                $query->where('status', 'succeeded');
            })
            ->with(['payment', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => [
                'orders' => $orders->items(),
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total(),
                ],
            ],
        ]);
    }

    /**
     * Get specific order details - only if payment succeeded
     */
    public function show(Request $request, int $orderId): JsonResponse
    {
        $user = $request->user();
        
        $order = Order::where('user_id', $user->id)
            ->where('id', $orderId)
            ->whereHas('payment', function($query) {
                $query->where('status', 'succeeded');
            })
            ->with([
                'payment',
                'orderItems.product.images',
                'city',
                'deliveryTimeSlot'
            ])
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found or payment not completed',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'order' => $this->formatOrderDetails($order),
            ],
        ]);
    }

    /**
     * Format order details for API response
     */
    private function formatOrderDetails(Order $order): array
    {
        return [
            'id' => $order->id,
            'status' => $order->status,
            'total_amount' => (float)$order->total_amount,
            'payment_method' => $order->payment_method,
            'shipping_address' => $order->shipping_address,
            'city' => $order->city ? [
                'id' => $order->city->id,
                'name' => $order->city->name,
            ] : null,
            'delivery_date' => $order->delivery_date,
            'delivery_time_slot' => $order->deliveryTimeSlot ? [
                'id' => $order->deliveryTimeSlot->id,
                'name' => $order->deliveryTimeSlot->name,
                'start_time' => $order->deliveryTimeSlot->start_time,
                'end_time' => $order->deliveryTimeSlot->end_time,
            ] : null,
            'location_description' => $order->location_description,
            'extra_notes' => $order->extra_notes,
            'is_gift' => $order->is_gift,
            'created_at' => $order->created_at,
            'updated_at' => $order->updated_at,
            'payment' => $order->payment ? [
                'id' => $order->payment->id,
                'status' => $order->payment->status,
                'amount' => (float)$order->payment->amount,
                'currency' => $order->payment->currency,
                'provider' => $order->payment->provider,
                'provider_txn_id' => $order->payment->provider_txn_id,
                'paid_at' => $order->payment->paid_at,
            ] : null,
            'items' => $order->orderItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'quantity' => $item->quantity,
                    'unit_price' => (float)$item->unit_price,
                    'total_price' => (float)$item->total_price,
                    'product' => [
                        'id' => $item->product->id,
                        'name' => $item->product->name,
                        'sku' => $item->product->sku,
                        'images' => $item->product->images->map(function ($image) {
                            return [
                                'id' => $image->id,
                                'url' => $image->url,
                                'alt_text' => $image->alt_text,
                            ];
                        }),
                    ],
                ];
            }),
        ];
    }

    /**
     * Cancel an order (if allowed) - only orders with succeeded payments
     */
    public function cancel(Request $request, int $orderId): JsonResponse
    {
        $user = $request->user();
        
        $order = Order::where('user_id', $user->id)
            ->where('id', $orderId)
            ->whereHas('payment', function($query) {
                $query->where('status', 'succeeded');
            })
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found or payment not completed',
            ], 404);
        }

        // Check if order can be cancelled
        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be cancelled at this stage',
            ], 422);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully',
            'data' => [
                'order' => $this->formatOrderDetails($order->fresh()),
            ],
        ]);
    }
}