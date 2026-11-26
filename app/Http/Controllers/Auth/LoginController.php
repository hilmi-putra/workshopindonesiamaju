<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request, CartService $cartService): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'Email atau kata sandi tidak valid.']);
        }

        $user = Auth::user();
        if (!$user->is_active) {
            Auth::logout();
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.']);
        }

        $request->session()->regenerate();
        $cartService->mergeSessionToDatabase($user);

        return redirect()->intended($user->getDashboardUrl())
            ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah keluar.');
    }
}
