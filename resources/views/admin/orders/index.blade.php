@extends('admin.layouts.app')

@section('title', 'Orders')

@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">

        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            {{-- Page Header (optional but matches discount page) --}}
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Orders</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li
                        class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="{{ route('admin.dashboard') }}" class="text-slate-400 dark:text-zink-200">Dashboard</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">Orders</li>
                </ul>
            </div>

            {{-- Alerts --}}
            <x-form-alerts />

            {{-- Filters Card --}}
            <div class="card mb-4">
              <div class="!py-3.5 card-body border-y border-dashed border-slate-200 dark:border-zink-500">
              <form method="GET" action="{{ route('admin.orders.index') }}"
                class="grid grid-cols-1 gap-4 md:grid-cols-12 items-end">
                
                <input type="text" name="search" class="md:col-span-4 form-input"
                placeholder="Search (ID, name, phone, email)" value="{{ request('search') }}">

                <select name="delivery_status" class="md:col-span-2 form-select">
                <option value="">All Delivery</option>
                @foreach ($deliveryStatuses as $ds)
                  <option value="{{ $ds }}" @selected(request('delivery_status') === $ds)>
                  {{ ucwords(str_replace('_', ' ', $ds)) }}</option>
                @endforeach
                </select>

                <div class="md:col-span-2 md:col-start-12 flex justify-end md:justify-end">
                <button class="btn bg-custom-500 text-white w-full md:w-auto">Filter</button>
                </div>
              </form>
              </div>
            </div>

            {{-- Table Card --}}
            <div class="card">
                <div class="card-body overflow-x-auto">
                    <table class="w-full whitespace-nowrap border-separate table-custom border-spacing-y-1">
                        <thead class="bg-slate-100 text-slate-600 dark:bg-zink-600">
                            <tr>
                                <th class="px-3.5 py-2.5 first:pl-5 last:pr-5">#</th>
                                <th class="px-3.5 py-2.5 first:pl-5 last:pr-5">Customer</th>
                                <th class="px-3.5 py-2.5 first:pl-5 last:pr-5">Location</th>
                                <th class="px-3.5 py-2.5 first:pl-5 last:pr-5">Payment</th>
                                <th class="px-3.5 py-2.5 first:pl-5 last:pr-5">Qty</th>
                                <th class="px-3.5 py-2.5 first:pl-5 last:pr-5">Total</th>
                                <th class="px-3.5 py-2.5 first:pl-5 last:pr-5">Delivery</th>
                                <th class="px-3.5 py-2.5 first:pl-5 last:pr-5">Placed</th>
                                <th class="px-3.5 py-2.5 first:pl-5 last:pr-5">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $o)
                                @php $qty = $o->items->sum('quantity'); @endphp
                                <tr
                                    class="relative rounded-md after:absolute ltr:after:border-l-2 rtl:after:border-r-2 ltr:after:left-0 rtl:after:right-0 after:top-0 after:bottom-0 after:border-transparent">
                                    <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                        #{{ str_pad((string) $o->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                        {{ $o->user->name ?? $o->receiver_name }}
                                        <div class="text-xs text-slate-500">{{ $o->user->email ?? $o->receiver_phone }}
                                        </div>
                                    </td>
                                    <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">{{ $o->city->name_en ?? '—' }}</td>
                                    <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                        {{ strtoupper($o->payment_method ?? '—') }}</td>
                                    <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">{{ $qty }}</td>
                                    <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                        ${{ number_format((float) $o->total_amount, 2) }}</td>
                                    <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                        <span
                                            class="px-2.5 py-0.5 rounded-full text-xs border
                    @class([
                        'bg-yellow-100 text-yellow-700' => $o->delivery_status === 'pending',
                        'bg-sky-100 text-sky-700' => $o->delivery_status === 'processing',
                        'bg-orange-100 text-orange-700' =>
                            $o->delivery_status === 'out_for_delivery',
                        'bg-green-100 text-green-700' => $o->delivery_status === 'delivered',
                        'bg-red-100 text-red-700' => $o->delivery_status === 'cancelled',
                    ])
                  ">{{ ucwords(str_replace('_', ' ', $o->delivery_status ?? 'pending')) }}</span>
                                    </td>
                                    <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                        {{ $o->created_at?->format('M d, Y H:i') }}</td>
                                    <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                        <a class="btn bg-slate-100 dark:bg-slate-500/20"
                                            href="{{ route('admin.orders.show', $o->id) }}">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-3.5 py-8 text-center">
                                        <div class="py-6 text-center">
                                            <i data-lucide="search"
                                                class="w-6 h-6 mx-auto text-sky-500 fill-sky-100 dark:fill-sky-500/20"></i>
                                            <h5 class="mt-2">Sorry! No Result Found</h5>
                                            <p class="text-slate-500 dark:text-zink-200">No orders found. Try adjusting your
                                                filters.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
