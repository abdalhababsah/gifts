<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderDeliveryStatusRequest;
use App\Services\Admin\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class OrderController extends Controller
{
    public function __construct(private OrderService $orders) {}

    public function index(Request $request)
    {
        try {
            $orders = $this->orders->list($request->only('status', 'delivery_status', 'search'), 15);
            $deliveryStatuses = $this->orders->deliveryStatusOptions();

            return view('admin.orders.index', compact('orders', 'deliveryStatuses'));
        } catch (Throwable $e) {
            Log::error('OrderController@index failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to load orders.');
        }
    }

    public function show(int $order)
    {
        try {
            $order = $this->orders->find($order);
            $deliveryStatuses = $this->orders->deliveryStatusOptions();

            return view('admin.orders.show', compact('order', 'deliveryStatuses'));
        } catch (Throwable $e) {
            Log::error('OrderController@show failed', ['order_id' => $order, 'error' => $e->getMessage()]);
            return redirect()->route('admin.orders.index')->with('error', 'Order not found.');
        }
    }

    public function updateDeliveryStatus(UpdateOrderDeliveryStatusRequest $request, int $order)
    {
        try {
            $updated = $this->orders->updateDeliveryStatus($order, $request->validated()['delivery_status']);
            return redirect()
                ->route('admin.orders.show', $updated->id)
                ->with('success', 'Delivery status updated successfully.');
        } catch (Throwable $e) {
            Log::error('OrderController@updateDeliveryStatus failed', ['order_id' => $order, 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to update delivery status.');
        }
    }

    public function print(int $order)
    {
        try {
            $order = $this->orders->invoiceData($order);
            return view('admin.orders.print', compact('order'));
        } catch (Throwable $e) {
            Log::error('OrderController@print failed', ['order_id' => $order, 'error' => $e->getMessage()]);
            return redirect()->route('admin.orders.index')->with('error', 'Failed to load invoice.');
        }
    }
}
