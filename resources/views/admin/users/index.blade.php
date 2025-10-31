@extends('admin.layouts.app')

@section('title', 'Users Management')

@section('content')
    <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Users Management</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="{{ route('admin.dashboard') }}" class="text-slate-400 dark:text-zink-200">Dashboard</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">Users</li>
                </ul>
            </div>

            <x-form-alerts />

            <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-2 xl:grid-cols-6">
                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center justify-center size-12 text-custom-500 bg-custom-100 rounded-md dark:bg-custom-500/20 shrink-0">
                                <i data-lucide="users"></i>
                            </div>
                            <div>
                                <h5 class="text-2xl leading-none">{{ number_format($statistics['total']) }}</h5>
                                <p class="text-slate-500 dark:text-zink-200">Total Users</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center justify-center size-12 text-green-500 bg-green-100 rounded-md dark:bg-green-500/20 shrink-0">
                                <i data-lucide="badge-check"></i>
                            </div>
                            <div>
                                <h5 class="text-2xl leading-none">{{ number_format($statistics['verified']) }}</h5>
                                <p class="text-slate-500 dark:text-zink-200">Email Verified</p>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="card">
                    <div class="card-body">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center justify-center size-12 text-amber-500 bg-amber-100 rounded-md dark:bg-amber-500/20 shrink-0">
                                <i data-lucide="mail-question"></i>
                            </div>
                            <div>
                                <h5 class="text-2xl leading-none">{{ number_format($statistics['unverified']) }}</h5>
                                <p class="text-slate-500 dark:text-zink-200">Pending Verification</p>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center justify-center size-12 text-sky-500 bg-sky-100 rounded-md dark:bg-sky-500/20 shrink-0">
                                <i data-lucide="calendar-plus"></i>
                            </div>
                            <div>
                                <h5 class="text-2xl leading-none">{{ number_format($statistics['new_this_month']) }}</h5>
                                <p class="text-slate-500 dark:text-zink-200">Joined This Month</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center justify-center size-12 text-purple-500 bg-purple-100 rounded-md dark:bg-purple-500/20 shrink-0">
                                <i data-lucide="shopping-bag"></i>
                            </div>
                            <div>
                                <h5 class="text-2xl leading-none">{{ number_format($statistics['with_orders']) }}</h5>
                                <p class="text-slate-500 dark:text-zink-200">Placed Orders</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card xl:col-span-2 xl:row-span-1 md:col-span-2">
                    <div class="card-body">
                        <h6 class="mb-3 text-15">Role Distribution</h6>
                        <ul class="space-y-2">
                            @forelse($statistics['role_breakdown'] as $role)
                                <li class="flex items-center justify-between">
                                    <span class="text-slate-500 dark:text-zink-200">{{ $role->name }}</span>
                                    <span class="text-slate-800 dark:text-zink-50 font-medium">{{ number_format($role->users_count) }}</span>
                                </li>
                            @empty
                                <li class="text-slate-500 dark:text-zink-200">No roles defined</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="flex flex-col gap-3 mb-4 md:flex-row md:items-center md:justify-between">
                        <h6 class="text-15">Users List</h6>
                        <div class="flex items-center gap-3">
                            <button type="button" id="openCreateUserModal"
                                class="flex items-center gap-2 text-white btn bg-custom-500 border-custom-500 hover:bg-custom-600 hover:border-custom-600 focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-500/20">
                                <i data-lucide="plus-circle" class="size-4"></i>
                                Add User
                            </button>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-5">
                        <div class="grid grid-cols-1 gap-3 xl:grid-cols-12">
                            <div class="xl:col-span-5">
                                <div class="relative">
                                    <input type="text" name="search"
                                        class="form-input border-slate-200 dark:border-zink-500 ltr:pl-10 rtl:pr-10 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Search (ID, name, email, phone)" value="{{ request('search') }}">
                                    <i data-lucide="search"
                                        class="absolute size-4 ltr:left-3 rtl:right-3 top-3 text-slate-500 dark:text-zink-200"></i>
                                </div>
                            </div>
                            <div class="xl:col-span-4">
                                <select name="role_id"
                                    class="form-select border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800">
                                    <option value="">All Roles</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" @selected((string) request('role_id') === (string) $role->id)>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="xl:col-span-3 xl:col-start-10">
                                <div class="flex gap-2">
                                    <button type="submit"
                                        class="flex items-center justify-center w-full px-3 py-2 text-slate-500 btn bg-slate-100 hover:text-white hover:bg-slate-600 focus:text-white focus:bg-slate-600 focus:ring focus:ring-slate-100 active:text-white active:bg-slate-600 active:ring active:ring-slate-100 dark:bg-slate-500/20 dark:text-slate-400 dark:hover:bg-slate-500 dark:hover:text-white">
                                        <i data-lucide="sliders-horizontal" class="size-4 mr-2"></i>
                                        Filter
                                    </button>
                                    <a href="{{ route('admin.users.index') }}"
                                        class="flex items-center justify-center px-3 py-2 text-slate-500 btn bg-white border border-slate-200 hover:bg-slate-100 dark:border-zink-500 dark:bg-zink-700 dark:text-zink-200">
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="-mx-5 -mb-5 overflow-x-auto">
                        <table class="w-full border-separate table-custom border-spacing-y-1 whitespace-nowrap">
                            <thead class="text-left">
                                <tr class="relative rounded-md bg-slate-100 dark:bg-zink-600 after:absolute ltr:after:border-l-2 rtl:after:border-r-2 ltr:after:left-0 rtl:after:right-0 after:top-0 after:bottom-0 after:border-transparent [&.active]:after:border-custom-500 [&.active]:bg-slate-100 dark:[&.active]:bg-zink-600">
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">User ID</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Name</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Email</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Phone</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Role</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Joined</th>
                                    {{-- <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Verification</th> --}}
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr class="relative rounded-md after:absolute ltr:after:border-l-2 rtl:after:border-r-2 ltr:after:left-0 rtl:after:right-0 after:top-0 after:bottom-0 after:border-transparent [&.active]:after:border-custom-500 [&.active]:bg-slate-100 dark:[&.active]:bg-zink-600"
                                        data-id="{{ $user->id }}">
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            <a href="#!"
                                                class="transition-all duration-150 ease-linear text-custom-500 hover:text-custom-600 user-id">
                                                #USR{{ str_pad((string) $user->id, 5, '0', STR_PAD_LEFT) }}
                                            </a>
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-medium">
                                            {{ $user->name }}
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            <div class="flex flex-col">
                                                <span>{{ $user->email }}</span>
                                                @if ($user->phone_number)
                                                    <span class="text-xs text-slate-500 dark:text-zink-200">
                                                        {{ $user->phone_number }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            {{ $user->phone_number ?? '—' }}
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            <span class="px-2.5 py-0.5 text-xs font-medium rounded border bg-slate-100 border-slate-200 text-slate-500 dark:bg-slate-500/20 dark:border-slate-500/20 dark:text-zink-200">
                                                {{ $user->role->name ?? 'Unassigned' }}
                                            </span>
                                        </td>
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            {{ $user->created_at?->format('d M, Y') ?? '—' }}
                                        </td>
                                        {{-- <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            @if ($user->email_verified_at)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-xs font-medium text-green-500 bg-green-100 rounded-full dark:bg-green-500/20">
                                                    <i data-lucide="check-circle" class="size-3"></i> Verified
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-xs font-medium text-amber-500 bg-amber-100 rounded-full dark:bg-amber-500/20">
                                                    <i data-lucide="clock" class="size-3"></i> Pending
                                                </span>
                                            @endif
                                        </td> --}}
                                        <td class="px-3.5 py-2.5 first:pl-5 last:pr-5">
                                            <div class="flex items-center gap-2">
                                                <button type="button"
                                                    class="px-2.5 py-1 text-xs font-medium text-white rounded bg-custom-500 hover:bg-custom-600 focus:ring focus:ring-custom-500/20"
                                                    onclick="openEditModal({{ $user->id }})">
                                                    Edit
                                                </button>
                                                {{-- <form id="deleteUserForm{{ $user->id }}"
                                                    action="{{ route('admin.users.destroy', $user) }}" method="POST" class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form> --}}
                                                {{-- <button type="button"
                                                    class="px-2.5 py-1 text-xs font-medium text-red-500 border border-red-200 rounded hover:bg-red-50 dark:border-red-500/30"
                                                    onclick="confirmDelete('deleteUserForm{{ $user->id }}', '{{ addslashes($user->name) }}')">
                                                    Delete
                                                </button> --}}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-3.5 py-6 text-center text-slate-500 dark:text-zink-200">
                                            No users found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-5 py-4  dark:border-zink-500">
                        {{ $users->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- User Modal --}}
    <div id="userModal"
        class="fixed inset-0 z-[1004] hidden items-center justify-center overflow-y-auto bg-slate-900/60 px-4 py-6">
        <div class="relative w-full max-w-2xl mx-auto">
            <div class="card">
                <div class="flex items-center justify-between card-header">
                    <h5 id="userModalTitle" class="text-16">Add User</h5>
                    <button type="button" data-modal-close="userModal"
                        class="text-slate-500 hover:text-slate-700 dark:text-zink-200 dark:hover:text-zink-50">
                        <i data-lucide="x" class="size-5"></i>
                    </button>
                </div>
                <div class="card-body space-y-6">
                    <form id="createUserForm" method="POST" action="{{ route('admin.users.store') }}">
                        @csrf
                        <input type="hidden" name="form_context" value="create">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="create_name" class="block mb-2 text-sm font-medium">Full Name</label>
                                <input type="text" id="create_name" name="name"
                                    value="{{ old('form_context') === 'create' ? old('name') : '' }}"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:border-custom-500 dark:focus:border-custom-500"
                                    placeholder="John Doe" required>
                                @if ($errors->has('name') && old('form_context') === 'create')
                                    <p class="mt-1 text-xs text-red-500">{{ $errors->first('name') }}</p>
                                @endif
                            </div>
                            <div>
                                <label for="create_email" class="block mb-2 text-sm font-medium">Email</label>
                                <input type="email" id="create_email" name="email"
                                    value="{{ old('form_context') === 'create' ? old('email') : '' }}"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:border-custom-500 dark:focus:border-custom-500"
                                    placeholder="john@example.com" required>
                                @if ($errors->has('email') && old('form_context') === 'create')
                                    <p class="mt-1 text-xs text-red-500">{{ $errors->first('email') }}</p>
                                @endif
                            </div>
                            <div>
                                <label for="create_phone_number" class="block mb-2 text-sm font-medium">Phone</label>
                                <input type="text" id="create_phone_number" name="phone_number"
                                    value="{{ old('form_context') === 'create' ? old('phone_number') : '' }}"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:border-custom-500 dark:focus:border-custom-500"
                                    placeholder="+1 555 000 0000">
                                @if ($errors->has('phone_number') && old('form_context') === 'create')
                                    <p class="mt-1 text-xs text-red-500">{{ $errors->first('phone_number') }}</p>
                                @endif
                            </div>
                            <div>
                                <label for="create_role_id" class="block mb-2 text-sm font-medium">Role</label>
                                <select id="create_role_id" name="role_id"
                                    class="form-select border-slate-200 dark:border-zink-500 focus:border-custom-500 dark:focus:border-custom-500">
                                    <option value="">Select role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            @selected(old('form_context') === 'create' && (string) old('role_id') === (string) $role->id)>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('role_id') && old('form_context') === 'create')
                                    <p class="mt-1 text-xs text-red-500">{{ $errors->first('role_id') }}</p>
                                @endif
                            </div>
                            <div>
                                <label for="create_password" class="block mb-2 text-sm font-medium">Password</label>
                                <input type="password" id="create_password" name="password"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:border-custom-500 dark:focus:border-custom-500"
                                    placeholder="Min 8 characters" required>
                                @if ($errors->has('password') && old('form_context') === 'create')
                                    <p class="mt-1 text-xs text-red-500">{{ $errors->first('password') }}</p>
                                @endif
                            </div>
                            <div>
                                <label for="create_password_confirmation" class="block mb-2 text-sm font-medium">Confirm Password</label>
                                <input type="password" id="create_password_confirmation" name="password_confirmation"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:border-custom-500 dark:focus:border-custom-500"
                                    placeholder="Repeat password" required>
                            </div>
                        </div>
                        <div class="flex justify-end gap-2 mt-6">
                            <button type="button" data-modal-close="userModal"
                                class="text-red-500 transition-all duration-200 ease-linear bg-white border-white btn hover:text-red-600 focus:text-red-600 active:text-red-600 dark:bg-zink-500 dark:border-zink-500">
                                Cancel
                            </button>
                            <button type="submit"
                                class="text-white transition-all duration-200 ease-linear btn bg-custom-500 border-custom-500 hover:bg-custom-600 hover:border-custom-600 focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-500/20">
                                Create User
                            </button>
                        </div>
                    </form>

                    <form id="editUserForm" method="POST" class="hidden">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="form_context" value="edit">
                        <input type="hidden" name="editing_user_id" id="edit_user_id"
                            value="{{ old('form_context') === 'edit' ? old('editing_user_id') : '' }}">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="edit_name" class="block mb-2 text-sm font-medium">Full Name</label>
                                <input type="text" id="edit_name" name="name"
                                    value="{{ old('form_context') === 'edit' ? old('name') : '' }}"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:border-custom-500 dark:focus:border-custom-500"
                                    placeholder="John Doe" required>
                                @if ($errors->has('name') && old('form_context') === 'edit')
                                    <p class="mt-1 text-xs text-red-500">{{ $errors->first('name') }}</p>
                                @endif
                            </div>
                            <div>
                                <label for="edit_email" class="block mb-2 text-sm font-medium">Email</label>
                                <input type="email" id="edit_email" name="email"
                                    value="{{ old('form_context') === 'edit' ? old('email') : '' }}"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:border-custom-500 dark:focus:border-custom-500"
                                    placeholder="john@example.com" required>
                                @if ($errors->has('email') && old('form_context') === 'edit')
                                    <p class="mt-1 text-xs text-red-500">{{ $errors->first('email') }}</p>
                                @endif
                            </div>
                            <div>
                                <label for="edit_phone_number" class="block mb-2 text-sm font-medium">Phone</label>
                                <input type="text" id="edit_phone_number" name="phone_number"
                                    value="{{ old('form_context') === 'edit' ? old('phone_number') : '' }}"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:border-custom-500 dark:focus:border-custom-500"
                                    placeholder="+1 555 000 0000">
                                @if ($errors->has('phone_number') && old('form_context') === 'edit')
                                    <p class="mt-1 text-xs text-red-500">{{ $errors->first('phone_number') }}</p>
                                @endif
                            </div>
                            <div>
                                <label for="edit_role_id" class="block mb-2 text-sm font-medium">Role</label>
                                <select id="edit_role_id" name="role_id"
                                    class="form-select border-slate-200 dark:border-zink-500 focus:border-custom-500 dark:focus:border-custom-500">
                                    <option value="">Select role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            @selected(old('form_context') === 'edit' && (string) old('role_id') === (string) $role->id)>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('role_id') && old('form_context') === 'edit')
                                    <p class="mt-1 text-xs text-red-500">{{ $errors->first('role_id') }}</p>
                                @endif
                            </div>
                            <div>
                                <label for="edit_password" class="block mb-2 text-sm font-medium">Password</label>
                                <input type="password" id="edit_password" name="password"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:border-custom-500 dark:focus:border-custom-500"
                                    placeholder="Leave blank to keep current password">
                                @if ($errors->has('password') && old('form_context') === 'edit')
                                    <p class="mt-1 text-xs text-red-500">{{ $errors->first('password') }}</p>
                                @endif
                            </div>
                            <div>
                                <label for="edit_password_confirmation" class="block mb-2 text-sm font-medium">Confirm Password</label>
                                <input type="password" id="edit_password_confirmation" name="password_confirmation"
                                    class="form-input border-slate-200 dark:border-zink-500 focus:border-custom-500 dark:focus:border-custom-500"
                                    placeholder="Repeat password">
                            </div>
                        </div>
                        <div class="flex justify-end gap-2 mt-6">
                            <button type="button" data-modal-close="userModal"
                                class="text-red-500 transition-all duration-200 ease-linear bg-white border-white btn hover:text-red-600 focus:text-red-600 active:text-red-600 dark:bg-zink-500 dark:border-zink-500">
                                Cancel
                            </button>
                            <button type="submit"
                                class="text-white transition-all duration-200 ease-linear btn bg-custom-500 border-custom-500 hover:bg-custom-600 hover:border-custom-600 focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-500/20">
                                Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@php
    $oldContext = old('form_context');
    $oldCreateData = $oldContext === 'create'
        ? [
            'name' => old('name'),
            'email' => old('email'),
            'phone_number' => old('phone_number'),
            'role_id' => old('role_id'),
        ]
        : null;
    $oldEditData = $oldContext === 'edit'
        ? [
            'id' => old('editing_user_id'),
            'name' => old('name'),
            'email' => old('email'),
            'phone_number' => old('phone_number'),
            'role_id' => old('role_id'),
        ]
        : null;
@endphp

@push('scripts')
    <script>
        const userCreateOld = @json($oldCreateData);
        const userEditOld = @json($oldEditData);

        const userModal = document.getElementById('userModal');
        const modalTitle = document.getElementById('userModalTitle');
        const createForm = document.getElementById('createUserForm');
        const editForm = document.getElementById('editUserForm');

        const createFields = {
            name: document.getElementById('create_name'),
            email: document.getElementById('create_email'),
            phone: document.getElementById('create_phone_number'),
            role: document.getElementById('create_role_id'),
            password: document.getElementById('create_password'),
            confirm: document.getElementById('create_password_confirmation'),
        };

        const editFields = {
            id: document.getElementById('edit_user_id'),
            name: document.getElementById('edit_name'),
            email: document.getElementById('edit_email'),
            phone: document.getElementById('edit_phone_number'),
            role: document.getElementById('edit_role_id'),
            password: document.getElementById('edit_password'),
            confirm: document.getElementById('edit_password_confirmation'),
        };

        function showModal() {
            if (!userModal) return;
            userModal.classList.remove('hidden');
            userModal.classList.add('flex');
        }

        function hideModal() {
            if (!userModal) return;
            userModal.classList.add('hidden');
            userModal.classList.remove('flex');
            if (editForm) {
                editForm.classList.add('hidden');
            }
            if (createForm) {
                createForm.classList.remove('hidden');
            }
        }

        function fillCreateForm(prefill) {
            if (!createForm) return;

            if (!prefill) {
                createForm.reset();
                createFields.role.value = '';
                return;
            }

            createFields.name.value = prefill.name ?? '';
            createFields.email.value = prefill.email ?? '';
            createFields.phone.value = prefill.phone_number ?? '';
            createFields.role.value = prefill.role_id ?? '';
        }

        function fillEditForm(prefill) {
            if (!prefill || !editForm) return;

            editFields.id.value = prefill.id ?? '';
            editFields.name.value = prefill.name ?? '';
            editFields.email.value = prefill.email ?? '';
            editFields.phone.value = prefill.phone_number ?? '';
            editFields.role.value = prefill.role_id ?? '';
            editFields.password.value = '';
            editFields.confirm.value = '';
        }

        function openCreateModal(prefill = null) {
            if (!createForm || !modalTitle) return;

            modalTitle.textContent = 'Add User';
            createForm.classList.remove('hidden');
            editForm.classList.add('hidden');
            fillCreateForm(prefill);
            showModal();
        }

        async function openEditModal(userId, prefill = null) {
            if (!editForm || !modalTitle) return;

            modalTitle.textContent = 'Edit User';
            editForm.action = `{{ url('admin/users') }}/${userId}`;
            editForm.classList.remove('hidden');
            createForm.classList.add('hidden');

            if (prefill) {
                fillEditForm(prefill);
                showModal();
                return;
            }

            try {
                const response = await fetch(`{{ url('admin/users') }}/${userId}`);
                const result = await response.json();

                if (result.success) {
                    fillEditForm(result.data);
                    showModal();
                }
            } catch (error) {
                console.error('Failed to load user details', error);
                alert('Unable to load user details. Please try again.');
            }
        }

        function confirmDelete(formId, userName) {
            const form = document.getElementById(formId);
            if (!form) return;

            if (confirm(`Are you sure you want to delete "${userName}"?`)) {
                form.submit();
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const createTrigger = document.getElementById('openCreateUserModal');
            if (createTrigger) {
                createTrigger.addEventListener('click', function (event) {
                    event.preventDefault();
                    openCreateModal();
                });
            }

            document.querySelectorAll('[data-modal-close="userModal"]').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    hideModal();
                });
            });

            if (userModal) {
                userModal.addEventListener('click', function (event) {
                    if (event.target === userModal) {
                        hideModal();
                    }
                });
            }

            if (userCreateOld) {
                openCreateModal(userCreateOld);
            }

            if (userEditOld && userEditOld.id) {
                openEditModal(userEditOld.id, userEditOld);
            }
        });

        window.openEditModal = openEditModal;
        window.confirmDelete = confirmDelete;
    </script>
@endpush
