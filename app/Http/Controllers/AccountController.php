<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAccountRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function __construct(protected UserService $userService) {}

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

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $this->userService->updatePassword($request->user(), $request->validated('password'));

        return back()->with('success', 'Password updated successfully.');
    }
}
