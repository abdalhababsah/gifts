<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends ApiController
{
    /**
     * Get user's orders with pagination - only orders with succeeded payments
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $orders = Order::where('user_id', $user->id)
                ->whereHas('payment', fn($q) => $q->where('status', 'succeeded'))
                ->with(['payment', 'orderItems.product.images'])
                ->orderByDesc('created_at')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'data'    => (new OrderCollection($orders))->toArray($request),
            ]);
        } catch (\Throwable $e) {
            Log::error('Orders index failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch orders at the moment.',
            ], 500);
        }
    }

    /**
     * Get specific order details - only if payment succeeded
     */
    public function show(Request $request, int $orderId): JsonResponse
    {
        try {
            $user = $request->user();

            $order = Order::where('user_id', $user->id)
                ->where('id', $orderId)
                ->whereHas('payment', fn($q) => $q->where('status', 'succeeded'))
                ->with(['payment', 'orderItems.product.images', 'city', 'deliveryTimeSlot'])
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found or payment not completed',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data'    => [
                    'order' => new OrderResource($order),
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Order show failed', ['order_id' => $orderId, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch this order at the moment.',
            ], 500);
        }
    }

    /**
     * Cancel an order (if allowed) - only orders with succeeded payments
     */
    public function cancel(Request $request, int $orderId): JsonResponse
    {
        try {
            $user = $request->user();

            $order = Order::where('user_id', $user->id)
                ->where('id', $orderId)
                ->whereHas('payment', fn($q) => $q->where('status', 'succeeded'))
                ->with(['payment', 'orderItems.product.images', 'city', 'deliveryTimeSlot'])
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found or payment not completed',
                ], 404);
            }

            if (!in_array($order->status, ['pending', 'confirmed'], true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order cannot be cancelled at this stage',
                ], 422);
            }

            $order->update(['status' => 'cancelled']);
            $order->refresh()->load(['payment', 'orderItems.product.images', 'city', 'deliveryTimeSlot']);

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully',
                'data'    => [
                    'order' => new OrderResource($order),
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Order cancel failed', ['order_id' => $orderId, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Unable to cancel this order at the moment.',
            ], 500);
        }
    }
}
