@extends('admin.layouts.app')

@section('content')
    <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Ecommerce Dashboard</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="#!" class="text-slate-400 dark:text-zink-200">Dashboards</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">
                        Ecommerce
                    </li>
                </ul>
            </div>

            <div class="grid grid-cols-12 2xl:grid-cols-12 gap-x-5">
                <!-- Welcome Banner -->
                <div class="relative col-span-12 overflow-hidden card 2xl:col-span-8 bg-slate-900">
                    <div class="absolute inset-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-100" version="1.1"
                            xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev/svgjs" width="1440"
                            height="560" preserveAspectRatio="none" viewBox="0 0 1440 560">
                            <g mask="url(&quot;#SvgjsMask1000&quot;)" fill="none">
                                <use xlink:href="#SvgjsSymbol1007" x="0" y="0"></use>
                                <use xlink:href="#SvgjsSymbol1007" x="720" y="0"></use>
                            </g>
                            <defs>
                                <mask id="SvgjsMask1000">
                                    <rect width="1440" height="560" fill="#ffffff"></rect>
                                </mask>
                                <path d="M-1 0 a1 1 0 1 0 2 0 a1 1 0 1 0 -2 0z" id="SvgjsPath1003"></path>
                                <path d="M-3 0 a3 3 0 1 0 6 0 a3 3 0 1 0 -6 0z" id="SvgjsPath1004"></path>
                                <path d="M-5 0 a5 5 0 1 0 10 0 a5 5 0 1 0 -10 0z" id="SvgjsPath1001"></path>
                                <path d="M2 -2 L-2 2z" id="SvgjsPath1005"></path>
                                <path d="M6 -6 L-6 6z" id="SvgjsPath1002"></path>
                                <path d="M30 -30 L-30 30z" id="SvgjsPath1006"></path>
                            </defs>
                            <symbol id="SvgjsSymbol1007">
                                <use xlink:href="#SvgjsPath1001" x="30" y="30" stroke="rgba(32, 43, 61, 1)"></use>
                                <use xlink:href="#SvgjsPath1002" x="30" y="90" stroke="rgba(32, 43, 61, 1)"></use>
                                <use xlink:href="#SvgjsPath1001" x="30" y="150" stroke="rgba(32, 43, 61, 1)"></use>
                            </symbol>
                        </svg>
                    </div>
                    <div class="relative card-body">
                        <div class="grid items-center grid-cols-12">
                            <div class="col-span-12 lg:col-span-8 2xl:col-span-7">
                                <h5 class="mb-3 font-normal tracking-wide text-slate-200">Welcome {{ auth()->user()->name ?? 'Admin' }} 🎉</h5>
                                <p class="mb-5 text-slate-400">Your ecommerce dashboard provides a clear overview of key financial and website KPIs at any time.</p>
                                <button type="button" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-500/20 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-500/20 dark:ring-custom-400/20">
                                    Explore Dashboard
                                </button>
                            </div>
                            <div class="hidden col-span-12 2xl:col-span-3 lg:col-span-2 lg:col-start-11 2xl:col-start-10 lg:block">
                                <img src="{{ asset('admin/assets/images/dashboard.png') }}" alt="" class="h-40 ltr:2xl:ml-auto rtl:2xl:mr-auto">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Statistics Chart -->
                <div class="col-span-12 card 2xl:col-span-4 2xl:row-span-2">
                    <div class="card-body">
                        <div class="flex items-center mb-3">
                            <h6 class="grow text-15">Order Statistics</h6>
                            <div class="relative">
                                <a href="#!" class="underline transition-all duration-200 ease-linear text-custom-500 hover:text-custom-600">
                                    View All <i data-lucide="move-right" class="inline-block align-middle size-4 ltr:ml-1 rtl:mr-1"></i>
                                </a>
                            </div>
                        </div>
                        <div id="orderStatisticsChart" class="apex-charts" data-chart-colors='["bg-purple-500", "bg-sky-500"]' dir="ltr"
                             data-delivered="{{ $dashboardData['orders']['delivered'] }}"
                             data-processing="{{ $dashboardData['orders']['processing'] }}">
                        </div>
                    </div>
                </div>

                <!-- Total Revenue Card -->
                <div class="col-span-12 card md:col-span-6 lg:col-span-3 2xl:col-span-2">
                    <div class="text-center card-body">
                        <div class="flex items-center justify-center mx-auto rounded-full size-14 bg-custom-100 text-custom-500 dark:bg-custom-500/20">
                            <i data-lucide="wallet-2"></i>
                        </div>
                        <h5 class="mt-4 mb-2">
                            $<span class="counter-value" data-target="{{ number_format($dashboardData['overview']['total_revenue'] / 1000, 2) }}">0</span>k
                        </h5>
                        <p class="text-slate-500 dark:text-zink-200">Total Revenue</p>
                    </div>
                </div>

                <!-- Total Orders Card -->
                <div class="col-span-12 card md:col-span-6 lg:col-span-3 2xl:col-span-2">
                    <div class="text-center card-body">
                        <div class="flex items-center justify-center mx-auto text-purple-500 bg-purple-100 rounded-full size-14 dark:bg-purple-500/20">
                            <i data-lucide="package"></i>
                        </div>
                        <h5 class="mt-4 mb-2">
                            <span class="counter-value" data-target="{{ $dashboardData['overview']['total_orders'] }}">0</span>
                        </h5>
                        <p class="text-slate-500 dark:text-zink-200">Total Orders</p>
                    </div>
                </div>

                <!-- Delivered Orders Card -->
                <div class="col-span-12 card md:col-span-6 lg:col-span-3 2xl:col-span-2">
                    <div class="text-center card-body">
                        <div class="flex items-center justify-center mx-auto text-green-500 bg-green-100 rounded-full size-14 dark:bg-green-500/20">
                            <i data-lucide="truck"></i>
                        </div>
                        <h5 class="mt-4 mb-2">
                            <span class="counter-value" data-target="{{ $dashboardData['overview']['delivered_orders'] }}">0</span>
                        </h5>
                        <p class="text-slate-500 dark:text-zink-200">Delivered</p>
                    </div>
                </div>

                <!-- Cancelled Orders Card -->
                <div class="col-span-12 card md:col-span-6 lg:col-span-3 2xl:col-span-2">
                    <div class="text-center card-body">
                        <div class="flex items-center justify-center mx-auto text-red-500 bg-red-100 rounded-full size-14 dark:bg-red-500/20">
                            <i data-lucide="package-x"></i>
                        </div>
                        <h5 class="mt-4 mb-2">
                            <span class="counter-value" data-target="{{ $dashboardData['overview']['cancelled_orders'] }}">0</span>
                        </h5>
                        <p class="text-slate-500 dark:text-zink-200">Cancelled</p>
                    </div>
                </div>

  

                <!-- Traffic Resources & Monthly Goal -->
                <div class="col-span-12 2xl:col-span-4">
                    <div class="grid grid-cols-12 gap-x-5">
                        <div class="col-span-12 card lg:col-span-6 2xl:col-span-12">
                            <div class="card-body">
                                <div class="flex items-center mb-3">
                                    <h6 class="grow text-15">Monthly Revenue Goal</h6>
                                </div>
                                <div class="flex items-center mb-2">
                                    <h5 class="grow">
                                        $<span class="counter-value" data-target="{{ number_format($dashboardData['revenue']['current_revenue']) }}">0</span>
                                    </h5>
                                    <span class="px-2.5 py-0.5 text-xs inline-block font-medium rounded border 
                                        @if($dashboardData['overview']['revenue_growth'] >= 0)
                                            bg-green-100 border-green-100 text-green-500 dark:bg-green-500/20 dark:border-green-900
                                        @else
                                            bg-red-100 border-red-100 text-red-500 dark:bg-red-500/20 dark:border-red-900
                                        @endif">
                                        <i data-lucide="{{ $dashboardData['overview']['revenue_growth'] >= 0 ? 'trending-up' : 'trending-down' }}" class="inline-block size-3 ltr:mr-1 rtl:ml-1"></i>
                                        {{ abs($dashboardData['overview']['revenue_growth']) }}%
                                    </span>
                                </div>
                                <h6 class="mb-0">Monthly Goal (${{ number_format($dashboardData['revenue']['monthly_goal']) }})</h6>
                                <div>
                                    <div class="flex items-center justify-between mt-5 mb-2">
                                        <p class="text-slate-500 dark:text-zink-200">Progress</p>
                                        <h6 class="mb-0 text-custom-500">{{ $dashboardData['revenue']['goal_percentage'] }}%</h6>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-2.5 dark:bg-zink-600">
                                        <div class="bg-custom-500 h-2.5 rounded-full" style="width: {{ $dashboardData['revenue']['goal_percentage'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Orders Table -->
                <div class="col-span-12 card 2xl:col-span-12">
                    <div class="card-body">
                        <div class="grid items-center grid-cols-1 gap-3 mb-5 2xl:grid-cols-12">
                            <div class="2xl:col-span-3">
                                <h6 class="text-15">Recent Orders</h6>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full whitespace-nowrap">
                                <thead class="ltr:text-left rtl:text-right bg-slate-100 text-slate-500 dark:text-zink-200 dark:bg-zink-600">
                                    <tr>
                                        <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold border-y border-slate-200 dark:border-zink-500">#</th>
                                        <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold border-y border-slate-200 dark:border-zink-500">Order ID</th>
                                        <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold border-y border-slate-200 dark:border-zink-500">Customer Name</th>
                                        <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold border-y border-slate-200 dark:border-zink-500">Location</th>
                                        <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold border-y border-slate-200 dark:border-zink-500">Order Date</th>
                                        <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold border-y border-slate-200 dark:border-zink-500">Payment</th>
                                        <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold border-y border-slate-200 dark:border-zink-500">Quantity</th>
                                        <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold border-y border-slate-200 dark:border-zink-500">Avg Price</th>
                                        <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold border-y border-slate-200 dark:border-zink-500">Total Amount</th>
                                        <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold border-y border-slate-200 dark:border-zink-500">Status</th>
                                        <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold border-y border-slate-200 dark:border-zink-500">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dashboardData['recent_orders'] as $index => $order)
                                    <tr>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">
                                            {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">
                                            {{-- <a href="{{ route('admin.orders.show', $order['id']) }}" class="text-custom-500 hover:text-custom-600">
                                                {{ $order['order_id'] }}
                                            </a> --}}
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">
                                            {{ $order['customer_name'] }}
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">
                                            {{ $order['location'] }}
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">
                                            {{ $order['order_date'] }}
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">
                                            {{ $order['payment_method'] }}
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">
                                            {{ $order['quantity'] }}
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">
                                            ${{ number_format($order['avg_price'], 2) }}
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">
                                            ${{ number_format($order['total_amount'], 2) }}
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">
                                            <span class="delivery_status px-2.5 py-0.5 text-xs inline-block font-medium rounded border {{ $order['status_class'] }}">
                                                {{ $order['delivery_status'] }}
                                            </span>
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">
                                            <div class="relative dropdown">
                                                <button id="orderAction{{ $order['id'] }}" data-bs-toggle="dropdown"
                                                        class="flex items-center justify-center size-[30px] dropdown-toggle p-0 text-slate-500 btn bg-slate-100 hover:text-white hover:bg-slate-600 focus:text-white focus:bg-slate-600 focus:ring focus:ring-slate-100 active:text-white active:bg-slate-600 active:ring active:ring-slate-100 dark:bg-slate-500/20 dark:text-slate-400 dark:hover:bg-slate-500 dark:hover:text-white dark:focus:bg-slate-500 dark:focus:text-white dark:active:bg-slate-500 dark:active:text-white dark:ring-slate-400/20">
                                                    <i data-lucide="more-horizontal" class="size-3"></i>
                                                </button>
                                                <ul class="absolute z-50 hidden py-2 mt-1 ltr:text-left rtl:text-right list-none bg-white rounded-md shadow-md dropdown-menu min-w-[10rem] dark:bg-zink-600"
                                                    aria-labelledby="orderAction{{ $order['id'] }}">
                                                    <li>
                                                        <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500 dark:text-zink-100 dark:hover:bg-zink-500 dark:hover:text-zink-200 dark:focus:bg-zink-500 dark:focus:text-zink-200"
                                                           href="{{ route('admin.orders.show', $order['id']) }}">
                                                            <i data-lucide="eye" class="inline-block size-3 ltr:mr-1 rtl:ml-1"></i>
                                                            <span class="align-middle">View</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="11" class="px-3.5 py-8 text-center border-y border-slate-200 dark:border-zink-500">
                                            <div class="text-slate-500 dark:text-zink-200">
                                                <i data-lucide="inbox" class="inline-block size-10 mb-2"></i>
                                                <p>No orders found</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Top Customers -->
                <div class="col-span-12 card lg:col-span-6 2xl:col-span-3">
                    <div class="card-body">
                        <div class="flex items-center mb-3">
                            <h6 class="grow text-15">Top Customers</h6>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mt-5 mb-2">
                                <p class="text-slate-500 dark:text-zink-200">
                                    {{ $dashboardData['revenue']['goal_percentage'] }}% of Goal (${{ number_format($dashboardData['revenue']['monthly_goal']) }})
                                </p>
                            </div>
                            <div class="w-full h-2 rounded-full bg-slate-200 dark:bg-zink-600">
                                <div class="h-2 bg-green-500 rounded-full" style="width: {{ $dashboardData['revenue']['goal_percentage'] }}%"></div>
                            </div>
                            <div class="grid mt-3 xl:grid-cols-2">
                                <div class="flex items-center gap-2">
                                    <div class="shrink-0">
                                        <i data-lucide="calendar-days" class="inline-block mb-1 align-middle size-4"></i>
                                    </div>
                                    <p class="mb-0 text-slate-500 dark:text-zink-200">
                                        This Month: <span class="font-medium text-slate-800 dark:text-zink-50">
                                            ${{ number_format($dashboardData['revenue']['current_revenue']) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <h6 class="mt-4 mb-3">Top Customers</h6>
                        <ul class="divide-y divide-slate-200 dark:divide-zink-500">
                            @forelse($dashboardData['top_customers'] as $customer)
                            <li class="flex items-center gap-3 py-2 first:pt-0 last:pb-0">
                                <div class="w-8 h-8 rounded-full shrink-0 bg-slate-100 dark:bg-zink-600">
                                    <img src="{{ $customer['avatar'] }}" alt="" class="w-8 h-8 rounded-full">
                                </div>
                                <div class="grow">
                                    <h6 class="font-medium">{{ $customer['name'] }}</h6>
                                    <p class="text-slate-500 dark:text-zink-200">{{ $customer['email'] }}</p>
                                </div>
                                <div class="shrink-0">
                                    <h6>${{ number_format($customer['total_spent'], 0) }}</h6>
                                </div>
                            </li>
                            @empty
                            <li class="py-4 text-center text-slate-500 dark:text-zink-200">
                                No customer data available
                            </li>
                            @endforelse
                        </ul>
                    </div>
                </div>


                <!-- Top Selling Products -->
                <div class="col-span-12 card lg:col-span-6 2xl:col-span-3">
                    <div class="card-body">
                        <div class="flex items-center mb-3">
                            <h6 class="grow text-15">Top Selling Products</h6>
                        </div>
                        <ul class="flex flex-col gap-5">
                            @forelse($dashboardData['top_products'] as $product)
                            <li class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-10 h-10 rounded-md bg-slate-100 dark:bg-zink-600">
                                    <img src="{{ $product['image'] }}" alt="" class="h-6">
                                </div>
                                <div class="overflow-hidden grow">
                                    <h6 class="truncate">{{ $product['name'] }}</h6>
                                    <div class="text-yellow-500">
                                        @for($i = 0; $i < 5; $i++)
                                            @if($i < floor($product['rating']))
                                                <i class="ri-star-fill"></i>
                                            @elseif($i < $product['rating'])
                                                <i class="ri-star-half-fill"></i>
                                            @else
                                                <i class="ri-star-line"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <h6 class="shrink-0">
                                    <i data-lucide="shopping-cart" class="inline-block align-middle size-4 text-slate-500 dark:text-zink-200 ltr:mr-1 rtl:ml-1"></i>
                                    {{ $product['total_sold'] }}
                                </h6>
                            </li>
                            @empty
                            <li class="py-4 text-center text-slate-500 dark:text-zink-200">
                                No product data available
                            </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Order Status Distribution -->
                <div class="col-span-12 card lg:col-span-6 2xl:col-span-3">
                    <div class="card-body">
                        <h6 class="relative mb-3 grow text-15">Order Status Distribution</h6>
                        <div id="orderStatusChart" class="apex-charts"
                             data-chart-colors='["bg-sky-500", "bg-orange-400", "bg-green-500", "bg-yellow-500", "bg-red-500"]'
                             data-labels='@json($dashboardData['order_status_chart']['labels'])'
                             data-series='@json($dashboardData['order_status_chart']['data'])'
                             dir="ltr">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Initialize counter animations
    document.addEventListener('DOMContentLoaded', function() {
        const counters = document.querySelectorAll('.counter-value');
        counters.forEach(counter => {
            const target = parseFloat(counter.getAttribute('data-target'));
            const duration = 2000;
            const increment = target / (duration / 16);
            let current = 0;
            
            const updateCounter = () => {
                current += increment;
                if (current < target) {
                    counter.textContent = current.toFixed(2);
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = target.toFixed(2);
                }
            };
            
            updateCounter();
        });
    });
</script>
@endpush
