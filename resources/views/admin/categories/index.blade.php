@extends('admin.layouts.app')

@section('title', 'Categories Management')

@section('content')
    <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            {{-- Page Header --}}
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Categories Management</h5>
        </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="{{ route('admin.dashboard') }}" class="text-slate-400 dark:text-zink-200">Dashboard</a>
                                </li>
                    <li class="text-slate-700 dark:text-zink-100">Categories</li>
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
                                <div class="flex items-center justify-center size-12 text-green-500 bg-green-100 rounded-md dark:bg-green-500/20">
                                    <i data-lucide="layers" class="size-5"></i>
                                </div>
                            </div>
                            <div class="grow">
                                <h5 class="mb-1 text-2xl leading-none">{{ $statistics['total'] }}</h5>
                                <p class="text-slate-500 dark:text-zink-200">Total Categories</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center gap-4">
                            <div class="shrink-0">
                                <div class="flex items-center justify-center size-12 text-sky-500 bg-sky-100 rounded-md dark:bg-sky-500/20">
                                    <i data-lucide="eye" class="size-5"></i>
                                </div>
                            </div>
                            <div class="grow">
                                <h5 class="mb-1 text-2xl leading-none">{{ $statistics['active'] }}</h5>
                                <p class="text-slate-500 dark:text-zink-200">Active Categories</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center gap-4">
                            <div class="shrink-0">
                                <div class="flex items-center justify-center size-12 text-red-500 bg-red-100 rounded-md dark:bg-red-500/20">
                                    <i data-lucide="eye-off" class="size-5"></i>
                                </div>
                            </div>
                            <div class="grow">
                                <h5 class="mb-1 text-2xl leading-none">{{ $statistics['inactive'] }}</h5>
                                <p class="text-slate-500 dark:text-zink-200">Inactive Categories</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center gap-4">
                            <div class="shrink-0">
                                <div class="flex items-center justify-center size-12 text-purple-500 bg-purple-100 rounded-md dark:bg-purple-500/20">
                                    <i data-lucide="package" class="size-5"></i>
                                </div>
                            </div>
                            <div class="grow">
                                <h5 class="mb-1 text-2xl leading-none">{{ $statistics['with_products'] }}</h5>
                                <p class="text-slate-500 dark:text-zink-200">With Products</p>
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
                        <h6 class="text-15 grow">Categories List</h6>
                                    <div class="shrink-0">
                            <button type="button" data-modal-target="addCategoryModal"
                                class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                                <i data-lucide="plus" class="inline-block size-4"></i>
                                <span class="align-middle">Add Category</span>
                            </button>
                                    </div>
                                </div>

                    {{-- Filter Bar --}}
                    <div class="!py-3.5 card-body border-y border-dashed border-slate-200 dark:border-zink-500 mt-3 mb-5">
                        <form method="GET" action="{{ route('admin.categories.index') }}">
                                    <div class="grid grid-cols-1 gap-5 xl:grid-cols-12">
                                <div class="relative xl:col-span-4">
                                    <input type="text" name="search"
                                                class="ltr:pl-8 rtl:pr-8 search form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Search categories..." autocomplete="off" value="{{ request('search') }}">
                                    <i data-lucide="search" class="inline-block size-4 absolute ltr:left-2.5 rtl:right-2.5 top-2.5 text-slate-500 dark:text-zink-200 fill-slate-100 dark:fill-zink-600"></i>
                                </div>

                                <div class="xl:col-span-3">
                                    <select name="status"
                                        class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200">
                                                <option value="">Select Status</option>
                                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
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

                    {{-- Categories Table --}}
                                <div class="-mx-5 -mb-5 overflow-x-auto">
                        <table class="w-full border-separate table-custom border-spacing-y-1 whitespace-nowrap">
                                        <thead class="text-left">
                                <tr class="relative rounded-md bg-slate-100 dark:bg-zink-600 after:absolute ltr:after:border-l-2 rtl:after:border-r-2 ltr:after:left-0 rtl:after:right-0 after:top-0 after:bottom-0 after:border-transparent [&.active]:after:border-custom-500 [&.active]:bg-slate-100 dark:[&.active]:bg-zink-600">
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Category ID</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Name (EN)</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Name (AR)</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Slug</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Products Count</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Status</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Created At</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                @forelse($categories as $category)
                                    <tr class="relative rounded-md after:absolute ltr:after:border-l-2 rtl:after:border-r-2 ltr:after:left-0 rtl:after:right-0 after:top-0 after:bottom-0 after:border-transparent [&.active]:after:border-custom-500 [&.active]:bg-slate-100 dark:[&.active]:bg-zink-600"
                                        data-id="{{ $category->id }}">
                                                <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            <a href="#!" class="transition-all duration-150 ease-linear text-custom-500 hover:text-custom-600 user-id">#CT{{ str_pad($category->id, 5, '0', STR_PAD_LEFT) }}</a>
                                                </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-medium name-en">{{ $category->name_en }}</td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 name-ar">{{ $category->name_ar }}</td>
                                                <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            <span class="px-2.5 py-0.5 text-xs font-medium rounded border bg-slate-100 border-slate-200 text-slate-500 dark:bg-slate-500/20 dark:border-slate-500/20 dark:text-zink-200">
                                                {{ $category->slug }}
                                            </span>
                                                </td>
                                                <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-500/20 dark:text-blue-400">
                                                {{ $category->products_count ?? 0 }} products
                                            </span>
                                                </td>
                                                <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            <div class="status-toggle-container" data-id="{{ $category->id }}">
                                                <button type="button"
                                                    class="status-toggle cursor-pointer inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-500 dark:bg-green-500/20 dark:border-transparent' : 'bg-red-100 text-red-500 dark:bg-red-500/20 dark:border-transparent' }}"
                                                    data-status="{{ $category->is_active ? 1 : 0 }}"
                                                    data-id="{{ $category->id }}"
                                                    data-name="{{ $category->name_en }}"
                                                    data-url="{{ route('admin.categories.toggleStatus', $category) }}"
                                                    onclick="confirmToggleStatus(this)">
                                                    <i data-lucide="{{ $category->is_active ? 'check-circle' : 'x-circle' }}" class="status-icon size-3 mr-1.5"></i>
                                                    <span class="status-text">{{ $category->is_active ? 'Active' : 'Inactive' }}</span>
                                                </button>
                                            </div>
                                                </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">{{ $category->created_at->format('d M, Y') }}</td>
                                                <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                                    <div class="relative dropdown">
                                                        <button
                                                            class="flex items-center justify-center size-[30px] dropdown-toggle p-0 text-slate-500 btn bg-slate-100 hover:text-white hover:bg-slate-600 focus:text-white focus:bg-slate-600 focus:ring focus:ring-slate-100 active:text-white active:bg-slate-600 active:ring active:ring-slate-100 dark:bg-slate-500/20 dark:text-slate-400 dark:hover:bg-slate-500 dark:hover:text-white dark:focus:bg-slate-500 dark:focus:text-white dark:active:bg-slate-500 dark:active:text-white dark:ring-slate-400/20"
                                                    id="categoryAction{{ $category->id }}" data-bs-toggle="dropdown">
                                                    <i data-lucide="more-horizontal" class="size-3"></i>
                                                </button>
                                                        <ul class="absolute z-50 hidden py-2 mt-1 ltr:text-left rtl:text-right list-none bg-white rounded-md shadow-md dropdown-menu min-w-[10rem] dark:bg-zink-600"
                                                    aria-labelledby="categoryAction{{ $category->id }}">
                                                            <li>
                                                                <a class="block px-4 py-1.5 text-base transition-all duration-200 ease-linear text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500 dark:text-zink-100 dark:hover:bg-zink-500 dark:hover:text-zink-200 dark:focus:bg-zink-500 dark:focus:text-zink-200"
                                                            href="#!" data-modal-target="addCategoryModal" onclick="openEditModal({{ $category->id }})">
                                                            <i data-lucide="file-edit" class="inline-block size-3 ltr:mr-1 rtl:ml-1"></i>
                                                            <span class="align-middle">Edit</span>
                                                        </a>
                                                            </li>
                                                            <li>
                                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="block w-full text-left px-4 py-1.5 text-base transition-all duration-200 ease-linear text-slate-600 dropdown-item hover:bg-slate-100 hover:text-slate-500 focus:bg-slate-100 focus:text-slate-500 dark:text-zink-100 dark:hover:bg-zink-500 dark:hover:text-zink-200 dark:focus:bg-zink-500 dark:focus:text-zink-200"
                                                                onclick="return confirm('Are you sure you want to delete {{ addslashes($category->name_en) }}? This action cannot be undone.')">
                                                                <i data-lucide="trash-2" class="inline-block size-3 ltr:mr-1 rtl:ml-1"></i>
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
                                        <td colspan="8" class="px-3.5 py-8 text-center">
                                            <div class="py-6 text-center">
                                                <i data-lucide="search" class="w-6 h-6 mx-auto text-sky-500 fill-sky-100 dark:fill-sky-500/20"></i>
                                                <h5 class="mt-2">Sorry! No Result Found</h5>
                                                    </div>
                                                </td>
                                            </tr>
                                @endforelse
                                        </tbody>
                                    </table>
                                        </div>

                    {{-- Pagination --}}
                    <x-pagination :paginator="$categories" />
            </div>
        </div>
                    </div>
    </div>

    {{-- Category Modals --}}
    <div id="addCategoryModal" modal-center class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4">
    <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">
        <div class="flex items-center justify-between p-4 border-b dark:border-zink-300/20">
                <h5 class="text-16" id="modalTitle">Add Category</h5>
                <button data-modal-close="addCategoryModal" class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500"><i data-lucide="x" class="size-5"></i></button>
        </div>
        <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
                    {{-- Create Category Form --}}
                    <form id="createCategoryForm" action="{{ route('admin.categories.store') }}" method="POST" class="create-form">
                        @csrf
                <div class="mb-3">
                            <label for="create_name_en" class="inline-block mb-2 text-base font-medium">Name (EN) <span class="text-red-500">*</span></label>
                            <input type="text" id="create_name_en" name="name_en" value="{{ old('name_en') }}"
                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="Enter name in English" required>
                            @error('name_en')
                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                            @enderror
                </div>

                <div class="mb-3">
                            <label for="create_name_ar" class="inline-block mb-2 text-base font-medium">Name (AR) <span class="text-red-500">*</span></label>
                            <input type="text" id="create_name_ar" name="name_ar" value="{{ old('name_ar') }}"
                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="Enter name in Arabic" dir="rtl" required>
                            @error('name_ar')
                                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                            @enderror
                </div>

                <div class="mb-3">
                            <label class="inline-flex items-center">
                                <input type="checkbox" id="create_is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm">Active</span>
                            </label>
                </div>

                        <div class="flex justify-end gap-2 mt-4">
                            <button type="reset" data-modal-close="addCategoryModal" class="text-red-500 transition-all duration-200 ease-linear bg-white border-white btn hover:text-red-600 focus:text-red-600 active:text-red-600 dark:bg-zink-500 dark:border-zink-500">Cancel</button>
                            <button type="submit" class="text-white transition-all duration-200 ease-linear btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Add Category</button>
                        </div>
                    </form>

                    {{-- Edit Category Form --}}
                    <form id="editCategoryForm" action="" method="POST" class="edit-form hidden">
                        @csrf
                        @method('PUT')
                <div class="mb-3">
                            <label for="edit_name_en" class="inline-block mb-2 text-base font-medium">Name (EN) <span class="text-red-500">*</span></label>
                            <input type="text" id="edit_name_en" name="name_en"
                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="Enter name in English" required>
                </div>

                <div class="mb-3">
                            <label for="edit_name_ar" class="inline-block mb-2 text-base font-medium">Name (AR) <span class="text-red-500">*</span></label>
                            <input type="text" id="edit_name_ar" name="name_ar"
                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                placeholder="Enter name in Arabic" dir="rtl" required>
                </div>

                <div class="mb-3">
                            <label class="inline-flex items-center">
                                <input type="checkbox" id="edit_is_active" name="is_active" value="1">
                                <span class="ml-2 text-sm">Active</span>
                            </label>
                </div>

                <div class="flex justify-end gap-2 mt-4">
                            <button type="reset" data-modal-close="addCategoryModal" class="text-red-500 transition-all duration-200 ease-linear bg-white border-white btn hover:text-red-600 focus:text-red-600 active:text-red-600 dark:bg-zink-500 dark:border-zink-500">Cancel</button>
                            <button type="submit" class="text-white transition-all duration-200 ease-linear btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Update Category</button>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        // Global variables
        let currentForm = 'create';
        

        
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
            document.getElementById('modalTitle').textContent = 'Add Category';
            document.getElementById('createCategoryForm').classList.remove('hidden');
            document.getElementById('editCategoryForm').classList.add('hidden');
            
            // Reset create form
            document.getElementById('createCategoryForm').reset();
            
            showModal('addCategoryModal');
        }

        function openEditModal(categoryId) {
            currentForm = 'edit';
            // Get the row element
            const row = document.querySelector(`tr[data-id="${categoryId}"]`);
            
            if (!row) {
                console.error('Category row not found');
                return;
            }
            
            // Get values from the row
            const nameEn = row.querySelector('.name-en').textContent.trim();
            const nameAr = row.querySelector('.name-ar').textContent.trim();
            const isActive = row.querySelector('.status-toggle').getAttribute('data-status') === '1';
            
            // Set form action
            const editForm = document.getElementById('editCategoryForm');
            editForm.action = `{{ url('admin/categories') }}/${categoryId}`;
            
            // Store action URL for validation error handling
            const hiddenActionInput = document.createElement('input');
            hiddenActionInput.type = 'hidden';
            hiddenActionInput.name = '_action';
            hiddenActionInput.value = editForm.action;
            editForm.appendChild(hiddenActionInput);
            
            // Set form values
            document.getElementById('edit_name_en').value = nameEn;
            document.getElementById('edit_name_ar').value = nameAr;
            document.getElementById('edit_is_active').checked = isActive;
            
            // Show edit form, hide create form
            document.getElementById('modalTitle').textContent = 'Edit Category';
            document.getElementById('createCategoryForm').classList.add('hidden');
            document.getElementById('editCategoryForm').classList.remove('hidden');
            
            showModal('addCategoryModal');
        }

        // Initialize event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Add Category button click
            document.querySelector('[data-modal-target="addCategoryModal"]').addEventListener('click', function(e) {
                e.preventDefault();
                openCreateModal();
            });

            // Close modal buttons
            document.querySelectorAll('[data-modal-close="addCategoryModal"]').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    hideModal('addCategoryModal');
                });
            });

            // Close modal when clicking outside
            document.getElementById('addCategoryModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    hideModal('addCategoryModal');
                }
            });

            // Close modals with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    hideModal('addCategoryModal');
                }
            });
        });

        // Auto-open create modal if there are validation errors for create
        @if ($errors->any() && !old('_method'))
            document.addEventListener('DOMContentLoaded', function() {
                openCreateModal();
            });
        @endif
        
        // Auto-open edit modal if there are validation errors for edit
        @if ($errors->any() && request()->is('*/categories/*') && !request()->isMethod('get'))
            document.addEventListener('DOMContentLoaded', function() {
                // Get the category ID from the form action in old input
                const actionUrl = "{{ old('_action') }}";
                const categoryId = actionUrl ? actionUrl.split('/').pop() : null;
                if (categoryId) {
                    openEditModal(categoryId);
                }
            });
        @endif
    </script>
@endpush