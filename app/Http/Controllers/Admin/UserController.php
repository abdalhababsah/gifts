<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Admin\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    /**
     * Display a listing of users with filtering options.
     */
    public function index(Request $request): View
    {
        $users = $this->userService->getPaginated($request);
        $roles = $this->userService->getRoles();
        $statistics = $this->userService->getStatistics();

        return view('admin.users.index', compact('users', 'roles', 'statistics'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'phone_number' => ['nullable', 'string', 'max:20', 'unique:users,phone_number'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'form_context' => ['nullable', 'in:create,edit'],
        ]);

        try {
            $this->userService->create($validated);
        } catch (Throwable $throwable) {
            report($throwable);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create user. Please try again.']);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user (for edit modal).
     */
    public function show(User $user): JsonResponse
    {
        $user->load('role');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'role_id' => $user->role_id,
                'role_name' => $user->role?->name,
            ],
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email,' . $user->id],
            'phone_number' => ['nullable', 'string', 'max:20', 'unique:users,phone_number,' . $user->id],
            'role_id' => ['nullable', 'exists:roles,id'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'form_context' => ['nullable', 'in:create,edit'],
            'editing_user_id' => ['nullable', 'integer'],
        ]);

        try {
            $this->userService->update($user, $validated);
        } catch (Throwable $throwable) {
            report($throwable);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update user. Please try again.']);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        try {
            $this->userService->delete($user);
        } catch (Throwable $throwable) {
            report($throwable);

            return redirect()->route('admin.users.index')
                ->with('error', 'Failed to delete user. Please try again.');
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
