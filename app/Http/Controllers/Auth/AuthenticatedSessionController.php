<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = $request->user();

        $usertype = strtolower(trim($user->usertype ?? ''));

        if ($usertype === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($usertype === 'student') {
            return redirect()->route('student.dashboard');
        }

        if ($usertype === 'lecturer') {
            return redirect()->route('lecturer.dashboard');
        }

        if ($usertype === 'supervisor') {
            return redirect()->route('supervisor.dashboard');
        }

        // Default fallback - redirect to home route
        return redirect()->route('home');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
