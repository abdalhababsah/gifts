@extends('admin.layouts.app')

@section('title', 'Products Management')

@section('content')
    <div
        class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            {{-- Page Header --}}
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Products Management</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li
                        class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="{{ route('admin.dashboard') }}" class="text-slate-400 dark:text-zink-200">Dashboard</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">Products</li>
                </ul>
            </div>

            {{-- Alert Messages --}}
            <x-form-alerts />

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 gap-x-5 md:grid-cols-2 xl:grid-cols-4 mb-5">
                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center gap-4">
                            <div class="shrink-0">
                                <div
                                    class="flex items-center justify-center size-12 text-green-500 bg-green-100 rounded-md dark:bg-green-500/20">
                                    <i data-lucide="package" class="size-5"></i>
                                </div>
                            </div>
                            <div class="grow">
                                <h5 class="mb-1 text-2xl leading-none">{{ $statistics['total'] }}</h5>
                                <p class="text-slate-500 dark:text-zink-200">Total Products</p>
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
                                    <i data-lucide="toggle-right" class="size-5"></i>
                                </div>
                            </div>
                            <div class="grow">
                                <div class="flex items-center gap-2">
                                    <h5 class="mb-1 text-2xl leading-none">{{ $statistics['active'] }}</h5>
                                    <span class="text-sm text-red-500">/</span>
                                    <h5 class="mb-1 text-xl leading-none text-red-500">{{ $statistics['inactive'] }}</h5>
                                </div>
                                <p class="text-slate-500 dark:text-zink-200">Active / Inactive</p>
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
                                    <i data-lucide="package-check" class="size-5"></i>
                                </div>
                            </div>
                            <div class="grow">
                                <div class="flex items-center gap-2">
                                    <h5 class="mb-1 text-2xl leading-none text-purple-500">{{ $statistics['in_stock'] }}</h5>
                                    <span class="text-sm text-slate-500">/</span>
                                    <h5 class="mb-1 text-xl leading-none text-orange-500">{{ $statistics['out_of_stock'] }}</h5>
                                </div>
                                <p class="text-slate-500 dark:text-zink-200">In Stock / Out of Stock</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center gap-4">
                            <div class="shrink-0">
                                <div
                                    class="flex items-center justify-center size-12 text-yellow-500 bg-yellow-100 rounded-md dark:bg-yellow-500/20">
                                    <i data-lucide="star" class="size-5"></i>
                                </div>
                            </div>
                            <div class="grow">
                                <h5 class="mb-1 text-2xl leading-none">{{ $statistics['featured'] }}</h5>
                                <p class="text-slate-500 dark:text-zink-200">Featured</p>
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
                        <h6 class="text-15 grow">Products List</h6>
                        <div class="shrink-0">
                            <a href="{{ route('admin.products.create') }}"
                                class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                                <i data-lucide="plus" class="inline-block size-4"></i>
                                <span class="align-middle">Add Product</span>
                            </a>
                        </div>
                    </div>

                    {{-- Filter Bar --}}
                    <div class="!py-3.5 card-body border-y border-dashed border-slate-200 dark:border-zink-500 mt-3 mb-5">
                        <form method="GET" action="{{ route('admin.products.index') }}">
                            <div class="grid grid-cols-1 gap-5 xl:grid-cols-12">
                                <div class="relative xl:col-span-3">
                                    <input type="text" name="search"
                                        class="ltr:pl-8 rtl:pr-8 search form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Search products..." autocomplete="off" value="{{ request('search') }}">
                                    <i data-lucide="search"
                                        class="inline-block size-4 absolute ltr:left-2.5 rtl:right-2.5 top-2.5 text-slate-500 dark:text-zink-200 fill-slate-100 dark:fill-zink-600"></i>
                                </div>

                                <div class="xl:col-span-1">
                                    <select name="status"
                                        class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                        <option value="">All Status</option>
                                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>

                                <div class="xl:col-span-1">
                                    <select name="featured"
                                        class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                        <option value="">All Featured</option>
                                        <option value="yes" {{ request('featured') === 'yes' ? 'selected' : '' }}>
                                            Featured
                                        </option>
                                        <option value="no" {{ request('featured') === 'no' ? 'selected' : '' }}>
                                            Not Featured</option>
                                    </select>
                                </div>

                                <div class="xl:col-span-2">
                                    <select name="stock_status"
                                        class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                        <option value="">All Stock</option>
                                        <option value="in_stock"
                                            {{ request('stock_status') === 'in_stock' ? 'selected' : '' }}>In Stock
                                        </option>
                                        <option value="out_of_stock"
                                            {{ request('stock_status') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock
                                        </option>
                                    </select>
                                </div>

                                <div class="xl:col-span-2">
                                    <select name="category_id"
                                        class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                        <option value="">All Categories</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name_en }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="xl:col-span-2">
                                    <select name="brand_id"
                                        class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                        <option value="">All Brands</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}"
                                                {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name_en }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="xl:col-span-1">
                                    <button type="submit"
                                        class="flex items-center justify-center w-full px-3 py-2 text-slate-500 btn bg-slate-100 hover:text-white hover:bg-slate-600 focus:text-white focus:bg-slate-600 focus:ring focus:ring-slate-100 active:text-white active:bg-slate-600 active:ring active:ring-slate-100 dark:bg-slate-500/20 dark:text-slate-400 dark:hover:bg-slate-500 dark:hover:text-white dark:focus:bg-slate-500 dark:focus:text-white dark:active:bg-slate-500 dark:active:text-white dark:ring-slate-400/20">
                                        <i data-lucide="sliders-horizontal" class="size-4"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Products Table --}}
                    <div class="-mx-5 -mb-5 overflow-x-auto">
                        <table class="w-full border-separate table-custom border-spacing-y-1 whitespace-nowrap">
                            <thead class="text-left">
                                <tr
                                    class="relative rounded-md bg-slate-100 dark:bg-zink-600 after:absolute ltr:after:border-l-2 rtl:after:border-r-2 ltr:after:left-0 rtl:after:right-0 after:top-0 after:bottom-0 after:border-transparent [&.active]:after:border-custom-500 [&.active]:bg-slate-100 dark:[&.active]:bg-zink-600">
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Product ID</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Image</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Product Info</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Category</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Brand</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Price</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Stock</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Status</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Featured</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Action</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @forelse($products as $product)
                                    <tr class="relative rounded-md after:absolute ltr:after:border-l-2 rtl:after:border-r-2 ltr:after:left-0 rtl:after:right-0 after:top-0 after:bottom-0 after:border-transparent [&.active]:after:border-custom-500 [&.active]:bg-slate-100 dark:[&.active]:bg-zink-600"
                                        data-id="{{ $product->id }}">
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            <a href="#!"
                                                class="transition-all duration-150 ease-linear text-custom-500 hover:text-custom-600 user-id">#PR{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</a>
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 product-image">
                                            <div
                                                class="flex items-center justify-center font-medium rounded-full size-10 shrink-0 bg-slate-200 text-slate-800 dark:text-zink-50 dark:bg-zink-600">
                                                @if ($product->cover_image_path)
                                                    <img src="{{ Storage::url($product->cover_image_path) }}"
                                                        alt="{{ $product->name_en }}"
                                                        class="h-10 w-10 rounded-full object-cover">
                                                @else
                                                    <i data-lucide="image" class="size-5 text-slate-400"></i>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            <div>
                                                <h6 class="mb-1 font-medium name-en">{{ $product->name_en }}</h6>
                                                <p class="text-slate-500 dark:text-zink-200 name-ar">
                                                    {{ $product->name_ar }}</p>
                                                <p class="text-xs text-slate-400 sku">SKU: {{ $product->sku }}</p>
                                            </div>
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            <span
                                                class="px-2.5 py-0.5 text-xs font-medium rounded border bg-slate-100 border-slate-200 text-slate-500 dark:bg-slate-500/20 dark:border-slate-500/20 dark:text-zink-200 category-name">
                                                {{ $product->category->name_en ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            <span
                                                class="px-2.5 py-0.5 text-xs font-medium rounded border bg-blue-100 border-blue-200 text-blue-500 dark:bg-blue-500/20 dark:border-blue-500/20 dark:text-blue-200 brand-name">
                                                {{ $product->brand->name_en ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-medium price">
                                            ${{ number_format($product->price, 2) }}
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->stock > 0 ? 'bg-green-100 text-green-500 dark:bg-green-500/20 dark:text-green-400' : 'bg-red-100 text-red-500 dark:bg-red-500/20 dark:text-red-400' }} stock">
                                                {{ $product->stock }}
                                                {{ $product->stock > 0 ? 'in stock' : 'out of stock' }}
                                            </span>
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            <div class="status-toggle-container" data-id="{{ $product->id }}">
                                                <button type="button"
                                                    class="status-toggle cursor-pointer inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-500 dark:bg-green-500/20 dark:border-transparent' : 'bg-red-100 text-red-500 dark:bg-red-500/20 dark:border-transparent' }}"
                                                    data-status="{{ $product->is_active ? 1 : 0 }}"
                                                    data-id="{{ $product->id }}" data-name="{{ $product->name_en }}"
                                                    data-url="{{ route('admin.products.toggleStatus', $product) }}"
                                                    onclick="confirmToggleStatus(this)">
                                                    <i data-lucide="{{ $product->is_active ? 'check-circle' : 'x-circle' }}"
                                                        class="status-icon size-3 mr-1.5"></i>
                                                    <span
                                                        class="status-text">{{ $product->is_active ? 'Active' : 'Inactive' }}</span>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            <div class="featured-toggle-container" data-id="{{ $product->id }}">
                                                <button type="button"
                                                    class="featured-toggle cursor-pointer inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_featured ? 'bg-yellow-100 text-yellow-500 dark:bg-yellow-500/20 dark:border-transparent' : 'bg-slate-100 text-slate-500 dark:bg-slate-500/20 dark:border-transparent' }}"
                                                    data-featured="{{ $product->is_featured ? 1 : 0 }}"
                                                    data-id="{{ $product->id }}" data-name="{{ $product->name_en }}"
                                                    data-url="{{ route('admin.products.toggleFeatured', $product) }}"
                                                    onclick="confirmToggleFeatured(this)">
                                                    <i data-lucide="{{ $product->is_featured ? 'star' : 'star-off' }}"
                                                        class="featured-icon size-3 mr-1.5"></i>
                                                    <span
                                                        class="featured-text">{{ $product->is_featured ? 'Featured' : 'Not Featured' }}</span>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            <div class="relative dropdown">
                                                <button
                                                    class="flex items-center justify-center size-[30px] dropdown-toggle p-0 text-slate-500 btn bg-slate-100 hover:text-white hover:bg-slate-600 focus:text-white focus:bg-slate-600 focus:ring focus:ring-slate-100 active:text-white active:bg-slate-600 active:ring active:ring-slate-100 dark:bg-slate-500/20 dark:text-slate-400 dark:hover:bg-slate-500 dark:hover:text-white dark:focus:bg-slate-500 dark:focus:text-white dark:active:bg-slate-500 dark:active:text-white dark:ring-slate-400/20"
                                                    id="productAction{{ $product->id }}" data-bs-toggle="dropdown">
                                                    <i data-lucide="more-horizontal" class="size-3"></i>
                                                </button>
                                                <ul class="absolute z-50 hidden py-2 mt-1 ltr:text-left rtl:text-right list-none bg-white rounded-md shadow-md dropdown-menu min-w-[10rem] dark:bg-zink-600"
                                                    aria-labelledby="productAction{{ $product->id }}">
                                                    <li>
                                                        <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500 dark:text-zink-100 dark:hover:bg-zink-500 dark:hover:text-zink-200 dark:focus:bg-zink-500 dark:focus:text-zink-200"
                                                            href="{{ route('admin.products.edit', $product) }}">
                                                            <i data-lucide="file-edit"
                                                                class="inline-block size-3 ltr:mr-1 rtl:ml-1"></i>
                                                            <span class="align-middle">Edit</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.products.destroy', $product) }}"
                                                            method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="block w-full text-left px-4 py-1.5 text-base transition-all duration-200 ease-linear text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500 dark:text-zink-100 dark:hover:bg-zink-500 dark:hover:text-zink-200 dark:focus:bg-zink-500 dark:focus:text-zink-200"
                                                                onclick="return confirm('Are you sure you want to delete {{ addslashes($product->name_en) }}? This action cannot be undone.')">
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
                                        <td colspan="10" class="px-3.5 py-8 text-center">
                                            <div class="py-6 text-center">
                                                <i data-lucide="search"
                                                    class="w-6 h-6 mx-auto text-sky-500 fill-sky-100 dark:fill-sky-500/20"></i>
                                                <h5 class="mt-2">Sorry! No Result Found</h5>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <x-pagination :paginator="$products" />
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('admin/assets/js/status-toggle.js') }}"></script>
    <script src="{{ asset('admin/assets/js/featured-toggle.js') }}"></script>
@endpush
