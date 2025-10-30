@extends('admin.layouts.app')

@section('title', 'Order #'.str_pad((string)$order->id, 6, '0', STR_PAD_LEFT))

@section('content')
<div
  class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">

  <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

    <div class="mt-5"></div>
    {{-- Alerts --}}
    <x-form-alerts />

    {{-- Page Header + Breadcrumbs --}}
    <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
      <div class="grow">
        <h5 class="text-16">
          Order #{{ str_pad((string)$order->id, 6, '0', STR_PAD_LEFT) }}
          <span class="align-middle ml-2 text-xs px-2.5 py-0.5 rounded-full border
            @class([
              'bg-yellow-100 text-yellow-700' => $order->status==='pending',
              'bg-sky-100 text-sky-700' => $order->status==='processing',
              'bg-green-100 text-green-700' => $order->status==='completed',
              'bg-red-100 text-red-700' => $order->status==='cancelled',
            ])">
            {{ ucfirst($order->status ?? 'pending') }}
          </span>
        </h5>
      </div>
      <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
        <li
          class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
          <a href="{{ route('admin.dashboard') }}" class="text-slate-400 dark:text-zink-200">Dashboard</a>
        </li>
        <li
          class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
          <a href="{{ route('admin.orders.index') }}" class="text-slate-400 dark:text-zink-200">Orders</a>
        </li>
        <li class="text-slate-700 dark:text-zink-100">#{{ str_pad((string)$order->id, 6, '0', STR_PAD_LEFT) }}</li>
      </ul>
    </div>

    {{-- Header Actions --}}
    <div class="flex justify-between items-center gap-3 pb-4 print:hidden">
      <div class="text-slate-500 dark:text-zink-200">
        Placed: {{ $order->created_at?->format('M d, Y H:i') ?? '—' }}
      </div>
      <div class="flex gap-2">
        <a href="{{ route('admin.orders.print', $order->id) }}" target="_blank"
           class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
          <i data-lucide="printer" class="inline-block size-4 mr-1"></i> Print Invoice
        </a>
        <a href="{{ route('admin.orders.index') }}"
           class="flex items-center justify-center px-3 py-2 text-slate-500 btn bg-slate-100 hover:text-white hover:bg-slate-600 focus:text-white focus:bg-slate-600 focus:ring focus:ring-slate-100 active:text-white active:bg-slate-600 active:ring active:ring-slate-100 dark:bg-slate-500/20 dark:text-slate-400 dark:hover:bg-slate-500 dark:hover:text-white dark:focus:bg-slate-500 dark:focus:text-white dark:active:bg-slate-500 dark:active:text-white dark:ring-slate-400/20">
          <i data-lucide="arrow-left" class="size-4 mr-1"></i> Back
        </a>
      </div>
    </div>

    <div class="grid grid-cols-12 gap-5">
      {{-- Left Column --}}
      <div class="col-span-12 lg:col-span-8">
        {{-- Items --}}
        <div class="card mb-4">
          <div class="card-body">
            <h6 class="mb-3 text-15">Items</h6>
            <div class="-mx-5 -mb-5 overflow-x-auto">
              <table class="w-full border-separate table-custom border-spacing-y-1 whitespace-nowrap">
                <thead class="text-left">
                  <tr class="rounded-md bg-slate-100 dark:bg-zink-600">
                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5">Product</th>
                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5">Qty</th>
                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5">Unit Price</th>
                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5">Total</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($order->items as $it)
                    <tr class="relative rounded-md after:absolute ltr:after:border-l-2 rtl:after:border-r-2 ltr:after:left-0 rtl:after:right-0 after:top-0 after:bottom-0 after:border-transparent">
                      <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                        <div class="flex flex-col">
                          <span class="font-medium">
                            {{ $it->product->name_en ?? ('Product #'.$it->product_id) }}
                          </span>
                          @if(!empty($it->variant_name))
                            <span class="text-xs text-slate-500 dark:text-zink-200">{{ $it->variant_name }}</span>
                          @endif
                        </div>
                      </td>
                      <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">{{ (int) $it->quantity }}</td>
                      <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                        ${{ number_format((float)($it->unit_price ?? 0), 2) }}
                      </td>
                      <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-medium">
                        ${{ number_format((float)($it->quantity * ($it->unit_price ?? 0)), 2) }}
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="4" class="px-3.5 py-8 text-center">
                        <div class="py-6 text-center">
                          <i data-lucide="search" class="w-6 h-6 mx-auto text-sky-500 fill-sky-100 dark:fill-sky-500/20"></i>
                          <h5 class="mt-2">No Items</h5>
                          <p class="text-slate-500 dark:text-zink-200">This order has no items.</p>
                        </div>
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        {{-- Notes --}}
        <div class="card">
          <div class="card-body">
            <h6 class="mb-3 text-15">Notes</h6>
            <p class="text-slate-600 dark:text-zink-100">{{ $order->extra_notes ?: '—' }}</p>
          </div>
        </div>
      </div>

      {{-- Right Column --}}
      <div class="col-span-12 lg:col-span-4">
        {{-- Customer --}}
        <div class="card mb-4">
          <div class="card-body">
            <h6 class="mb-3 text-15">Customer</h6>
            <div class="text-sm space-y-1">
              <div><span class="font-medium">Name:</span> {{ $order->user->name ?? $order->receiver_name ?? '—' }}</div>
              <div><span class="font-medium">Email:</span> {{ $order->user->email ?? '—' }}</div>
              <div><span class="font-medium">Phone:</span> {{ $order->receiver_phone ?? '—' }}</div>
              <div><span class="font-medium">Gift:</span> {{ $order->is_gift ? 'Yes' : 'No' }}</div>
            </div>
          </div>
        </div>

        {{-- Delivery --}}
        <div class="card mb-4">
          <div class="card-body">
            <h6 class="mb-3 text-15">Delivery</h6>
            <div class="text-sm space-y-2">
              <div><span class="font-medium">Address:</span> {{ $order->shipping_address ?? '—' }}</div>
              <div><span class="font-medium">City:</span> {{ $order->city->name_en ?? '—' }}</div>
              <div><span class="font-medium">Date:</span> {{ $order->delivery_date?->format('M d, Y') ?? '—' }}</div>
              <div>
                <span class="font-medium">Time Slot:</span>
                @if($order->deliveryTimeSlot)
                  {{ \Illuminate\Support\Str::of($order->deliveryTimeSlot->window_start)->substr(0,5) }}
                  –
                  {{ \Illuminate\Support\Str::of($order->deliveryTimeSlot->window_end)->substr(0,5) }}
                @else
                  —
                @endif
              </div>

              <div class="pt-2">
                <form method="POST" action="{{ route('admin.orders.updateDeliveryStatus', $order->id) }}" class="grid grid-cols-1 gap-2 sm:grid-cols-12">
                  @csrf
                  @method('PUT')
                  <div class="sm:col-span-8">
                    <label class="block text-xs mb-1">Delivery Status</label>
                    <select name="delivery_status" class="form-select">
                      @foreach($deliveryStatuses as $ds)
                        <option value="{{ $ds }}" @selected($order->delivery_status===$ds)>{{ ucwords(str_replace('_',' ',$ds)) }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="sm:col-span-4 sm:self-end">
                    <button class="w-full text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                      Update
                    </button>
                  </div>
                </form>
              </div>

              <div class="pt-1">
                <span class="font-medium">Current:</span>
                <span class="px-2.5 py-0.5 text-xs font-medium rounded border
                  @class([
                    'bg-yellow-100 border-yellow-200 text-yellow-700' => $order->delivery_status==='pending',
                    'bg-sky-100 border-sky-200 text-sky-700' => $order->delivery_status==='processing',
                    'bg-orange-100 border-orange-200 text-orange-700' => $order->delivery_status==='out_for_delivery',
                    'bg-green-100 border-green-200 text-green-700' => $order->delivery_status==='delivered',
                    'bg-red-100 border-red-200 text-red-700' => $order->delivery_status==='cancelled',
                  ])">
                  {{ ucwords(str_replace('_',' ',$order->delivery_status ?? 'pending')) }}
                </span>
              </div>
            </div>
          </div>
        </div>

        {{-- Payment & Totals --}}
        <div class="card">
          <div class="card-body">
            <h6 class="mb-3 text-15">Payment & Totals</h6>
            @php
              $itemsQty = (int) $order->items->sum('quantity');
              $subtotal = (float) ($order->subtotal ?? ($order->total_amount ?? 0));
              $shipping = (float) ($order->shipping_fee ?? 0);
              $discount = (float) ($order->discount_total ?? 0);
              $tax = (float) ($order->tax_total ?? 0);
              $grand = (float) ($order->total_amount ?? ($subtotal + $shipping + $tax - $discount));
            @endphp
            <div class="text-sm space-y-1">
              <div><span class="font-medium">Payment:</span> {{ strtoupper($order->payment_method ?? '—') }}</div>
              <div><span class="font-medium">Items:</span> {{ $itemsQty }}</div>
              <div class="flex justify-between"><span class="font-medium">Subtotal:</span> <span>${{ number_format($subtotal, 2) }}</span></div>
              <div class="flex justify-between"><span class="font-medium">Shipping:</span> <span>${{ number_format($shipping, 2) }}</span></div>
              <div class="flex justify-between"><span class="font-medium">Tax:</span> <span>${{ number_format($tax, 2) }}</span></div>
              <div class="flex justify-between"><span class="font-medium">Discount:</span> <span>-${{ number_format($discount, 2) }}</span></div>
              <hr class="my-2 border-dashed border-slate-200 dark:border-zink-500">
              <div class="flex justify-between text-base"><span class="font-semibold">Total:</span> <span class="font-semibold">${{ number_format($grand, 2) }}</span></div>
              <div><span class="font-medium">Order Status:</span> {{ ucfirst($order->status ?? 'pending') }}</div>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>
@endsection
