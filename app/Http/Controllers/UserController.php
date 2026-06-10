<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected ActivityLogService $activityLogService
    ) {}

    public function index(): View
    {
        return view('pages.users.index', [
            'title' => 'Users',
        ]);
    }

    public function data(): JsonResponse
    {
        return $this->userService->getDataTable();
    }

    public function create(): View
    {
        return view('pages.users.create', [
            'title' => 'Create User',
            'user' => new User(['status' => true]),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = $this->userService->create($request->validated());

        $this->activityLogService->log(
            'user',
            'created',
            'User '.$user->name.' created',
            $user->email,
            route('users.edit', $user)
        );

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        return view('pages.users.edit', [
            'title' => 'Edit User',
            'user' => $user,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->userService->update($user, $request->validated());

        $this->activityLogService->log(
            'user',
            'updated',
            'User '.$user->name.' updated',
            null,
            route('users.edit', $user)
        );

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $name = $user->name;
        $this->userService->delete($user);

        $this->activityLogService->log(
            'user',
            'deleted',
            'User '.$name.' deleted'
        );

        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
