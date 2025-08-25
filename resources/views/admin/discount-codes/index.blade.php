@extends('admin.layouts.app')

@section('title', 'Discount Codes Management')

@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            {{-- Page Header --}}
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Discount Codes Management</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li
                        class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="{{ route('admin.dashboard') }}" class="text-slate-400 dark:text-zink-200">Dashboard</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">Discount Codes</li>
                </ul>
            </div>

            {{-- Alert Messages --}}
            <x-form-alerts />

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 gap-x-5 md:grid-cols-2 xl:grid-cols-6 mb-5">
                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center gap-4">
                            <div class="shrink-0">
                                <div
                                    class="flex items-center justify-center size-12 text-green-500 bg-green-100 rounded-md dark:bg-green-500/20">
                                    <i data-lucide="ticket" class="size-5"></i>
                                </div>
                            </div>
                            <div class="grow">
                                <h5 class="mb-1 text-2xl leading-none">{{ $statistics['total'] }}</h5>
                                <p class="text-slate-500 dark:text-zink-200">Total Codes</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center gap-4">
                            <div class="shrink-0">
                                <div
                                    class="flex items-center justify-center size-12 text-sky-500 bg-sky-100 rounded-md dark:bg-sky-500/20">
                                    <i data-lucide="check-circle" class="size-5"></i>
                                </div>
                            </div>
                            <div class="grow">
                                <h5 class="mb-1 text-2xl leading-none">{{ $statistics['active'] }}</h5>
                                <p class="text-slate-500 dark:text-zink-200">Active</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center gap-4">
                            <div class="shrink-0">
                                <div
                                    class="flex items-center justify-center size-12 text-red-500 bg-red-100 rounded-md dark:bg-red-500/20">
                                    <i data-lucide="x-circle" class="size-5"></i>
                                </div>
                            </div>
                            <div class="grow">
                                <h5 class="mb-1 text-2xl leading-none">{{ $statistics['inactive'] }}</h5>
                                <p class="text-slate-500 dark:text-zink-200">Inactive</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center gap-4">
                            <div class="shrink-0">
                                <div
                                    class="flex items-center justify-center size-12 text-purple-500 bg-purple-100 rounded-md dark:bg-purple-500/20">
                                    <i data-lucide="shopping-cart" class="size-5"></i>
                                </div>
                            </div>
                            <div class="grow">
                                <h5 class="mb-1 text-2xl leading-none">{{ $statistics['used'] }}</h5>
                                <p class="text-slate-500 dark:text-zink-200">Used</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center gap-4">
                            <div class="shrink-0">
                                <div
                                    class="flex items-center justify-center size-12 text-orange-500 bg-orange-100 rounded-md dark:bg-orange-500/20">
                                    <i data-lucide="calendar-x" class="size-5"></i>
                                </div>
                            </div>
                            <div class="grow">
                                <h5 class="mb-1 text-2xl leading-none">{{ $statistics['expired'] }}</h5>
                                <p class="text-slate-500 dark:text-zink-200">Expired</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center gap-4">
                            <div class="shrink-0">
                                <div
                                    class="flex items-center justify-center size-12 text-blue-500 bg-blue-100 rounded-md dark:bg-blue-500/20">
                                    <i data-lucide="calendar-clock" class="size-5"></i>
                                </div>
                            </div>
                            <div class="grow">
                                <h5 class="mb-1 text-2xl leading-none">{{ $statistics['upcoming'] }}</h5>
                                <p class="text-slate-500 dark:text-zink-200">Upcoming</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content Card --}}
            <div class="card">
                <div class="card-body">
                    {{-- Table Header with Filters --}}
                    <div class="flex items-center">
                        <h6 class="text-15 grow">Discount Codes List</h6>
                        <div class="shrink-0">
                            <button type="button" data-modal-target="addDiscountCodeModal"
                                class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                                <i data-lucide="plus" class="inline-block size-4"></i>
                                <span class="align-middle">Add Discount Code</span>
                            </button>
                        </div>
                    </div>

                    {{-- Filter Bar --}}
                    <div class="!py-3.5 card-body border-y border-dashed border-slate-200 dark:border-zink-500 mt-3 mb-5">
                        <form method="GET" action="{{ route('admin.discount-codes.index') }}">
                            <div class="grid grid-cols-1 gap-5 xl:grid-cols-12">
                                <div class="relative xl:col-span-4">
                                    <input type="text" name="search"
                                        class="ltr:pl-8 rtl:pr-8 search form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Search discount codes..." autocomplete="off"
                                        value="{{ request('search') }}">
                                    <i data-lucide="search"
                                        class="inline-block size-4 absolute ltr:left-2.5 rtl:right-2.5 top-2.5 text-slate-500 dark:text-zink-200 fill-slate-100 dark:fill-zink-600"></i>
                                </div>

                                <div class="xl:col-span-2">
                                    <select name="status"
                                        class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                        <option value="">All Status</option>
                                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>

                                <div class="xl:col-span-2">
                                    <select name="type"
                                        class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                        <option value="">All Types</option>
                                        <option value="percent" {{ request('type') === 'percent' ? 'selected' : '' }}>Percentage</option>
                                        <option value="fixed" {{ request('type') === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                    </select>
                                </div>

                                <div class="xl:col-span-2 xl:col-start-11">
                                    <button type="submit"
                                        class="flex items-center justify-center w-full px-3 py-2 text-slate-500 btn bg-slate-100 hover:text-white hover:bg-slate-600 focus:text-white focus:bg-slate-600 focus:ring focus:ring-slate-100 active:text-white active:bg-slate-600 active:ring active:ring-slate-100 dark:bg-slate-500/20 dark:text-slate-400 dark:hover:bg-slate-500 dark:hover:text-white dark:focus:bg-slate-500 dark:focus:text-white dark:active:bg-slate-500 dark:active:text-white dark:ring-slate-400/20">
                                        <i data-lucide="sliders-horizontal" class="size-4 mr-2"></i>
                                        Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Discount Codes Table --}}
                    <div class="-mx-5 -mb-5 overflow-x-auto">
                        <table class="w-full border-separate table-custom border-spacing-y-1 whitespace-nowrap">
                            <thead class="text-left">
                                <tr
                                    class="relative rounded-md bg-slate-100 dark:bg-zink-600 after:absolute ltr:after:border-l-2 rtl:after:border-r-2 ltr:after:left-0 rtl:after:right-0 after:top-0 after:bottom-0 after:border-transparent [&.active]:after:border-custom-500 [&.active]:bg-slate-100 dark:[&.active]:bg-zink-600">
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Code ID</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Code</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Type</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Value</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Min Order</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Usage</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Valid Period</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Status</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Action</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @forelse($discountCodes as $discountCode)
                                    <tr class="relative rounded-md after:absolute ltr:after:border-l-2 rtl:after:border-r-2 ltr:after:left-0 rtl:after:right-0 after:top-0 after:bottom-0 after:border-transparent [&.active]:after:border-custom-500 [&.active]:bg-slate-100 dark:[&.active]:bg-zink-600"
                                        data-id="{{ $discountCode->id }}">
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            <a href="#!"
                                                class="transition-all duration-150 ease-linear text-custom-500 hover:text-custom-600 discount-id">#DC{{ str_pad($discountCode->id, 5, '0', STR_PAD_LEFT) }}</a>
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            <div class="flex flex-col">
                                                <span class="font-medium code">{{ $discountCode->code }}</span>
                                                @if($discountCode->description)
                                                    <span class="text-xs text-slate-500 dark:text-zink-200 description">{{ Str::limit($discountCode->description, 30) }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            <span class="px-2.5 py-0.5 text-xs font-medium rounded border {{ $discountCode->type === 'percent' ? 'bg-purple-100 border-purple-200 text-purple-500 dark:bg-purple-500/20 dark:border-purple-500/20' : 'bg-green-100 border-green-200 text-green-500 dark:bg-green-500/20 dark:border-green-500/20' }} type">
                                                {{ $discountCode->type === 'percent' ? 'Percentage' : 'Fixed Amount' }}
                                            </span>
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-medium">
                                            <span class="value">
                                                @if($discountCode->type === 'percent')
                                                    {{ number_format($discountCode->value, 0) }}%
                                                @else
                                                    ${{ number_format($discountCode->value, 2) }}
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            <span class="min-order">
                                                @if($discountCode->min_order_total)
                                                    ${{ number_format($discountCode->min_order_total, 2) }}
                                                @else
                                                    <span class="text-slate-400">No minimum</span>
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            <div class="flex flex-col gap-1">
                                                <span class="text-xs">
                                                    Used: <span class="font-medium">{{ $discountCode->order_discounts_count ?? 0 }}</span>
                                                    @if($discountCode->usage_limit)
                                                        / <span class="usage-limit">{{ $discountCode->usage_limit }}</span>
                                                    @else
                                                        <span class="text-slate-400">/ ∞</span>
                                                    @endif
                                                </span>
                                                @if($discountCode->per_user_limit)
                                                    <span class="text-xs text-slate-500">
                                                        Per user: <span class="per-user-limit">{{ $discountCode->per_user_limit }}</span>
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            <div class="flex flex-col gap-1 text-xs">
                                                @if($discountCode->start_date || $discountCode->end_date)
                                                    @if($discountCode->start_date)
                                                        <span class="start-date">From: {{ $discountCode->start_date->format('M d, Y') }}</span>
                                                    @endif
                                                    @if($discountCode->end_date)
                                                        <span class="end-date {{ $discountCode->end_date->isPast() ? 'text-red-500' : '' }}">
                                                            Until: {{ $discountCode->end_date->format('M d, Y') }}
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="text-slate-400">Always valid</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            <div class="status-toggle-container" data-id="{{ $discountCode->id }}">
                                                <button type="button"
                                                    class="status-toggle cursor-pointer inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $discountCode->is_active ? 'bg-green-100 text-green-500 dark:bg-green-500/20 dark:border-transparent' : 'bg-red-100 text-red-500 dark:bg-red-500/20 dark:border-transparent' }}"
                                                    data-status="{{ $discountCode->is_active ? 1 : 0 }}"
                                                    data-id="{{ $discountCode->id }}" 
                                                    data-code="{{ $discountCode->code }}"
                                                    data-url="{{ route('admin.discount-codes.toggleStatus', $discountCode) }}"
                                                    onclick="confirmToggleStatus(this)">
                                                    <i data-lucide="{{ $discountCode->is_active ? 'check-circle' : 'x-circle' }}"
                                                        class="status-icon size-3 mr-1.5"></i>
                                                    <span class="status-text">{{ $discountCode->is_active ? 'Active' : 'Inactive' }}</span>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            <div class="relative dropdown">
                                                <button
                                                    class="flex items-center justify-center size-[30px] dropdown-toggle p-0 text-slate-500 btn bg-slate-100 hover:text-white hover:bg-slate-600 focus:text-white focus:bg-slate-600 focus:ring focus:ring-slate-100 active:text-white active:bg-slate-600 active:ring active:ring-slate-100 dark:bg-slate-500/20 dark:text-slate-400 dark:hover:bg-slate-500 dark:hover:text-white dark:focus:bg-slate-500 dark:focus:text-white dark:active:bg-slate-500 dark:active:text-white dark:ring-slate-400/20"
                                                    id="discountCodeAction{{ $discountCode->id }}" data-bs-toggle="dropdown">
                                                    <i data-lucide="more-horizontal" class="size-3"></i>
                                                </button>
                                                <ul class="absolute z-50 hidden py-2 mt-1 ltr:text-left rtl:text-right list-none bg-white rounded-md shadow-md dropdown-menu min-w-[10rem] dark:bg-zink-600"
                                                    aria-labelledby="discountCodeAction{{ $discountCode->id }}">
                                                    <li>
                                                        <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500 dark:text-zink-100 dark:hover:bg-zink-500 dark:hover:text-zink-200 dark:focus:bg-zink-500 dark:focus:text-zink-200"
                                                            href="#!" data-modal-target="addDiscountCodeModal"
                                                            onclick="openEditModal({{ $discountCode->id }})">
                                                            <i data-lucide="file-edit"
                                                                class="inline-block size-3 ltr:mr-1 rtl:ml-1"></i>
                                                            <span class="align-middle">Edit</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.discount-codes.destroy', $discountCode) }}"
                                                            method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="block w-full text-left px-4 py-1.5 text-base transition-all duration-200 ease-linear text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500 dark:text-zink-100 dark:hover:bg-zink-500 dark:hover:text-zink-200 dark:focus:bg-zink-500 dark:focus:text-zink-200"
                                                                onclick="return confirm('Are you sure you want to delete the discount code {{ addslashes($discountCode->code) }}? This action cannot be undone.')">
                                                                <i data-lucide="trash-2"
                                                                    class="inline-block size-3 ltr:mr-1 rtl:ml-1"></i>
                                                                <span class="align-middle">Delete</span>
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-3.5 py-8 text-center">
                                            <div class="py-6 text-center">
                                                <i data-lucide="search"
                                                    class="w-6 h-6 mx-auto text-sky-500 fill-sky-100 dark:fill-sky-500/20"></i>
                                                <h5 class="mt-2">Sorry! No Result Found</h5>
                                                <p class="text-slate-500 dark:text-zink-200">No discount codes found. Try adjusting your filters or create a new discount code.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <x-pagination :paginator="$discountCodes" />
                </div>
            </div>
        </div>
    </div>

    {{-- Add/Edit Discount Code Modal --}}
    <div id="addDiscountCodeModal" modal-center
        class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4">
        <div class="w-screen md:w-[40rem] bg-white shadow rounded-md dark:bg-zink-600 max-h-[calc(theme('height.screen')_-_2rem)] flex flex-col">
            <div class="flex items-center justify-between p-4 border-b dark:border-zink-300/20">
                <h5 class="text-16" id="modalTitle">Add Discount Code</h5>
                <button data-modal-close="addDiscountCodeModal"
                    class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500">
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>
            <div class="overflow-y-auto p-4">
                {{-- Create Discount Code Form --}}
                <form id="createDiscountCodeForm" action="{{ route('admin.discount-codes.store') }}" method="POST"
                    class="create-form">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="md:col-span-2">
                            <label for="create_code" class="inline-block mb-2 text-base font-medium">
                                Discount Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="create_code" name="code" value="{{ old('code') }}"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="Enter discount code (e.g., SUMMER20)" required>
                            @error('code')
                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="create_description" class="inline-block mb-2 text-base font-medium">
                                Description
                            </label>
                            <textarea id="create_description" name="description" rows="2"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="Enter discount description (optional)">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="create_type" class="inline-block mb-2 text-base font-medium">
                                Discount Type <span class="text-red-500">*</span>
                            </label>
                            <select id="create_type" name="type" required
                                class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800"
                                onchange="updateValueLabel('create')">
                                <option value="">Select Type</option>
                                <option value="percent" {{ old('type') === 'percent' ? 'selected' : '' }}>Percentage</option>
                                <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                            </select>
                            @error('type')
                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="create_value" class="inline-block mb-2 text-base font-medium">
                                <span id="create_value_label">Discount Value</span> <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="create_value" name="value" value="{{ old('value') }}"
                                step="0.01" min="0" max="100"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="Enter value" required>
                            @error('value')
                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="create_min_order_total" class="inline-block mb-2 text-base font-medium">
                                Minimum Order Total
                            </label>
                            <input type="number" id="create_min_order_total" name="min_order_total" 
                                value="{{ old('min_order_total') }}" step="0.01" min="0"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="0.00">
                            @error('min_order_total')
                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="create_usage_limit" class="inline-block mb-2 text-base font-medium">
                                Total Usage Limit
                            </label>
                            <input type="number" id="create_usage_limit" name="usage_limit" 
                                value="{{ old('usage_limit') }}" min="1"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="Unlimited">
                            @error('usage_limit')
                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="create_per_user_limit" class="inline-block mb-2 text-base font-medium">
                                Per User Limit
                            </label>
                            <input type="number" id="create_per_user_limit" name="per_user_limit" 
                                value="{{ old('per_user_limit') }}" min="1"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="Unlimited">
                            @error('per_user_limit')
                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="create_start_date" class="inline-block mb-2 text-base font-medium">
                                Start Date
                            </label>
                            <input type="datetime-local" id="create_start_date" name="start_date" 
                                value="{{ old('start_date') }}"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                            @error('start_date')
                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="create_end_date" class="inline-block mb-2 text-base font-medium">
                                End Date
                            </label>
                            <input type="datetime-local" id="create_end_date" name="end_date" 
                                value="{{ old('end_date') }}"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                            @error('end_date')
                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="inline-flex items-center gap-2 text-base font-medium">
                                <input type="checkbox" id="create_is_active" name="is_active" value="1"
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                    class="border rounded-sm appearance-none size-4 bg-slate-100 border-slate-200 dark:bg-zink-600 dark:border-zink-500 checked:bg-custom-500 checked:border-custom-500 dark:checked:bg-custom-500 dark:checked:border-custom-500 checked:disabled:bg-custom-400 checked:disabled:border-custom-400">
                                <span>Active</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="reset" data-modal-close="addDiscountCodeModal"
                            class="text-red-500 transition-all duration-200 ease-linear bg-white border-white btn hover:text-red-600 focus:text-red-600 active:text-red-600 dark:bg-zink-500 dark:border-zink-500">
                            Cancel
                        </button>
                        <button type="submit"
                            class="text-white transition-all duration-200 ease-linear btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                            Add Discount Code
                        </button>
                    </div>
                </form>

                {{-- Edit Discount Code Form --}}
                <form id="editDiscountCodeForm" action="" method="POST" class="edit-form hidden">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="md:col-span-2">
                            <label for="edit_code" class="inline-block mb-2 text-base font-medium">
                                Discount Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="edit_code" name="code"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="Enter discount code" required>
                        </div>

                        <div class="md:col-span-2">
                            <label for="edit_description" class="inline-block mb-2 text-base font-medium">
                                Description
                            </label>
                            <textarea id="edit_description" name="description" rows="2"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="Enter discount description (optional)"></textarea>
                        </div>

                        <div>
                            <label for="edit_type" class="inline-block mb-2 text-base font-medium">
                                Discount Type <span class="text-red-500">*</span>
                            </label>
                            <select id="edit_type" name="type" required
                                class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800"
                                onchange="updateValueLabel('edit')">
                                <option value="">Select Type</option>
                                <option value="percent">Percentage</option>
                                <option value="fixed">Fixed Amount</option>
                            </select>
                        </div>

                        <div>
                            <label for="edit_value" class="inline-block mb-2 text-base font-medium">
                                <span id="edit_value_label">Discount Value</span> <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="edit_value" name="value"
                                step="0.01" min="0" max="100"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="Enter value" required>
                        </div>

                        <div>
                            <label for="edit_min_order_total" class="inline-block mb-2 text-base font-medium">
                                Minimum Order Total
                            </label>
                            <input type="number" id="edit_min_order_total" name="min_order_total"
                                step="0.01" min="0"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="0.00">
                        </div>

                        <div>
                            <label for="edit_usage_limit" class="inline-block mb-2 text-base font-medium">
                                Total Usage Limit
                            </label>
                            <input type="number" id="edit_usage_limit" name="usage_limit"
                                min="1"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="Unlimited">
                        </div>

                        <div>
                            <label for="edit_per_user_limit" class="inline-block mb-2 text-base font-medium">
                                Per User Limit
                            </label>
                            <input type="number" id="edit_per_user_limit" name="per_user_limit"
                                min="1"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="Unlimited">
                        </div>

                        <div>
                            <label for="edit_start_date" class="inline-block mb-2 text-base font-medium">
                                Start Date
                            </label>
                            <input type="datetime-local" id="edit_start_date" name="start_date"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                        </div>

                        <div>
                            <label for="edit_end_date" class="inline-block mb-2 text-base font-medium">
                                End Date
                            </label>
                            <input type="datetime-local" id="edit_end_date" name="end_date"
                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                        </div>

                        <div class="md:col-span-2">
                            <label class="inline-flex items-center gap-2 text-base font-medium">
                                <input type="checkbox" id="edit_is_active" name="is_active" value="1"
                                    class="border rounded-sm appearance-none size-4 bg-slate-100 border-slate-200 dark:bg-zink-600 dark:border-zink-500 checked:bg-custom-500 checked:border-custom-500 dark:checked:bg-custom-500 dark:checked:border-custom-500 checked:disabled:bg-custom-400 checked:disabled:border-custom-400">
                                <span>Active</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="reset" data-modal-close="addDiscountCodeModal"
                            class="text-red-500 transition-all duration-200 ease-linear bg-white border-white btn hover:text-red-600 focus:text-red-600 active:text-red-600 dark:bg-zink-500 dark:border-zink-500">
                            Cancel
                        </button>
                        <button type="submit"
                            class="text-white transition-all duration-200 ease-linear btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                            Update Discount Code
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Global variables
        let currentForm = 'create';

        // Update value label based on discount type
        function updateValueLabel(prefix) {
            const typeSelect = document.getElementById(prefix + '_type');
            const valueLabel = document.getElementById(prefix + '_value_label');
            const valueInput = document.getElementById(prefix + '_value');
            
            if (typeSelect.value === 'percent') {
                valueLabel.textContent = 'Percentage (%)';
                valueInput.setAttribute('max', '100');
                valueInput.setAttribute('placeholder', 'Enter percentage (0-100)');
            } else if (typeSelect.value === 'fixed') {
                valueLabel.textContent = 'Fixed Amount ($)';
                valueInput.removeAttribute('max');
                valueInput.setAttribute('placeholder', 'Enter amount');
            } else {
                valueLabel.textContent = 'Discount Value';
                valueInput.setAttribute('placeholder', 'Enter value');
            }
        }

        // Modal Functions
        function showModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
            modal.classList.add('show');
        }

        function hideModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('show');
            modal.classList.add('hidden');
        }

        function openCreateModal() {
            currentForm = 'create';
            document.getElementById('modalTitle').textContent = 'Add Discount Code';
            document.getElementById('createDiscountCodeForm').classList.remove('hidden');
            document.getElementById('editDiscountCodeForm').classList.add('hidden');

            // Reset create form
            document.getElementById('createDiscountCodeForm').reset();
            updateValueLabel('create');

            showModal('addDiscountCodeModal');
        }

        function openEditModal(discountCodeId) {
            currentForm = 'edit';
            
            // Fetch discount code data via AJAX
            fetch(`{{ url('admin/discount-codes') }}/${discountCodeId}`)
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        const discountCode = result.data;
                        
                        // Set form action
                        const editForm = document.getElementById('editDiscountCodeForm');
                        editForm.action = `{{ url('admin/discount-codes') }}/${discountCodeId}`;

                        // Set form values
                        document.getElementById('edit_code').value = discountCode.code;
                        document.getElementById('edit_description').value = discountCode.description || '';
                        document.getElementById('edit_type').value = discountCode.type;
                        document.getElementById('edit_value').value = discountCode.value;
                        document.getElementById('edit_min_order_total').value = discountCode.min_order_total || '';
                        document.getElementById('edit_usage_limit').value = discountCode.usage_limit || '';
                        document.getElementById('edit_per_user_limit').value = discountCode.per_user_limit || '';
                        
                        // Format dates for datetime-local input
                        if (discountCode.start_date) {
                            const startDate = new Date(discountCode.start_date);
                            document.getElementById('edit_start_date').value = startDate.toISOString().slice(0, 16);
                        }
                        if (discountCode.end_date) {
                            const endDate = new Date(discountCode.end_date);
                            document.getElementById('edit_end_date').value = endDate.toISOString().slice(0, 16);
                        }
                        
                        document.getElementById('edit_is_active').checked = discountCode.is_active;

                        // Update value label based on type
                        updateValueLabel('edit');

                        // Show edit form, hide create form
                        document.getElementById('modalTitle').textContent = 'Edit Discount Code';
                        document.getElementById('createDiscountCodeForm').classList.add('hidden');
                        document.getElementById('editDiscountCodeForm').classList.remove('hidden');

                        showModal('addDiscountCodeModal');
                    }
                })
                .catch(error => {
                    console.error('Error fetching discount code:', error);
                    alert('Failed to load discount code data');
                });
        }

        // Confirm toggle status
        function confirmToggleStatus(button) {
            const code = button.getAttribute('data-code');
            const currentStatus = button.getAttribute('data-status') === '1';
            const newStatus = currentStatus ? 'inactive' : 'active';
            
            if (confirm(`Are you sure you want to make the discount code "${code}" ${newStatus}?`)) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = button.getAttribute('data-url');
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Initialize event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Add Discount Code button click
            document.querySelector('[data-modal-target="addDiscountCodeModal"]').addEventListener('click', function(e) {
                e.preventDefault();
                openCreateModal();
            });

            // Close modal buttons
            document.querySelectorAll('[data-modal-close="addDiscountCodeModal"]').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    hideModal('addDiscountCodeModal');
                });
            });

            // Close modal when clicking outside
            document.getElementById('addDiscountCodeModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    hideModal('addDiscountCodeModal');
                }
            });

            // Close modals with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    hideModal('addDiscountCodeModal');
                }
            });

            // Initialize value labels on page load
            updateValueLabel('create');
            updateValueLabel('edit');
        });

        // Auto-open create modal if there are validation errors for create
        @if ($errors->any() && !old('_method'))
            document.addEventListener('DOMContentLoaded', function() {
                openCreateModal();
            });
        @endif

        // Auto-open edit modal if there are validation errors for edit
        @if ($errors->any() && old('_method') === 'PUT')
            document.addEventListener('DOMContentLoaded', function() {
                const discountCodeId = {{ request()->route('discount_code')->id ?? 'null' }};
                if (discountCodeId) {
                    openEditModal(discountCodeId);
                }
            });
        @endif
    </script>
@endpush