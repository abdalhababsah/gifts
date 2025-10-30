<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class UserService
{
    /**
     * Get paginated users with optional search & role filters.
     */
    public function getPaginated(Request $request): LengthAwarePaginator
    {
        $query = User::query()->with('role')->latest();

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone_number', 'like', '%' . $search . '%');

                if (is_numeric($search)) {
                    $q->orWhere('id', (int) $search);
                }
            });
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->input('role_id'));
        }

        return $query->paginate(12)->withQueryString();
    }

    /**
     * Fetch all roles for select options.
     */
    public function getRoles(): Collection
    {
        return Role::orderBy('name')->get();
    }

    /**
     * Gather high-level dashboard statistics for users.
     */
    public function getStatistics(): array
    {
        $totalUsers = User::count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $newThisMonth = User::where('created_at', '>=', Carbon::now()->startOfMonth())->count();
        $customersWithOrders = User::has('orders')->count();

        $roleBreakdown = Role::withCount('users')
            ->orderBy('name')
            ->get();

        return [
            'total' => $totalUsers,
            'verified' => $verifiedUsers,
            'unverified' => max($totalUsers - $verifiedUsers, 0),
            'new_this_month' => $newThisMonth,
            'with_orders' => $customersWithOrders,
            'role_breakdown' => $roleBreakdown,
        ];
    }

    /**
     * Create a new user from validated data.
     */
    public function create(array $data): User
    {
        $payload = Arr::only($data, ['name', 'email', 'phone_number', 'role_id', 'password']);
        $payload['phone_number'] = isset($payload['phone_number']) && $payload['phone_number'] !== ''
            ? $payload['phone_number']
            : null;
        $payload['role_id'] = isset($payload['role_id']) && $payload['role_id'] !== ''
            ? $payload['role_id']
            : null;

        /** @var \App\Models\User $user */
        $user = User::create($payload);

        $user->forceFill([
            'email_verified_at' => Carbon::now(),
        ])->save();

        return $user->refresh();
    }

    /**
     * Update an existing user with validated data.
     */
    public function update(User $user, array $data): User
    {
        $payload = Arr::only($data, ['name', 'email', 'phone_number', 'role_id', 'password']);
        $payload['phone_number'] = isset($payload['phone_number']) && $payload['phone_number'] !== ''
            ? $payload['phone_number']
            : null;
        $payload['role_id'] = isset($payload['role_id']) && $payload['role_id'] !== ''
            ? $payload['role_id']
            : null;

        if (empty($payload['password'])) {
            unset($payload['password']);
        }

        $user->fill($payload);
        $user->save();

        return $user->refresh();
    }

    /**
     * Delete the given user.
     */
    public function delete(User $user): void
    {
        $user->delete();
    }
}
