<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAccountRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Services\ActivityLogService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected ActivityLogService $activityLogService
    ) {}

    public function settings(): View
    {
        return view('pages.account.settings', [
            'title' => 'Account Settings',
            'user' => auth()->user(),
        ]);
    }

    public function updateProfile(UpdateAccountRequest $request): RedirectResponse
    {
        $user = $request->user();

        $this->userService->update(
            $user,
            $request->validated(),
            $request->file('avatar'),
            $request->boolean('remove_avatar')
        );

        $this->activityLogService->log(
            'user',
            'updated',
            'Profile updated',
            $user->email,
            route('account.settings')
        );

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $this->userService->updatePassword($request->user(), $request->validated('password'));

        $this->activityLogService->log(
            'auth',
            'updated',
            'Password changed',
            $request->user()->email,
            route('account.settings')
        );

        return back()->with('success', 'Password updated successfully.');
    }
}
