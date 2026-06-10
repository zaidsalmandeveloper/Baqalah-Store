<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(protected ActivityLogService $activityLogService) {}

    public function showLogin(): View
    {
        return view('pages.auth.signin', [
            'title' => 'Sign In',
        ]);
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'These credentials do not match our records.']);
        }

        $user = Auth::user();

        if (! $user->status) {
            Auth::logout();

            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'Your account has been deactivated. Please contact an administrator.']);
        }

        $request->session()->regenerate();

        $this->activityLogService->log(
            'auth',
            'login',
            $user->name.' signed in',
            $user->email
        );

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user) {
            $this->activityLogService->log(
                'auth',
                'logout',
                $user->name.' signed out',
                $user->email
            );
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
