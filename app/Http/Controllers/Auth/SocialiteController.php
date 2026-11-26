<?php

namespace App\Http\Controllers\Auth;

use App\Enums\AuthProvider;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CartService;
use GuzzleHttp\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect(): RedirectResponse
    {
        if (!config('services.google.client_id') || !config('services.google.client_secret')) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Login Google belum dikonfigurasi pada server ini.']);
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback(CartService $cartService): RedirectResponse
    {
        try {
            $driver = Socialite::driver('google');

            if (app()->environment('local')) {
                $driver->setHttpClient(new Client([
                    'curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4],
                    'verify' => false,
                ]));
            }

            $googleUser = $driver->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName() ?: 'Pengguna Workshop Indonesia Maju',
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt(Str::random(24)),
                    'provider' => AuthProvider::Google->value,
                    'provider_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'role' => UserRole::User,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]);
            } else {
                $user->update([
                    'provider' => AuthProvider::Google->value,
                    'provider_id' => $googleUser->getId(),
                    'avatar' => $user->avatar ?? $googleUser->getAvatar(),
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ]);
            }

            if (!$user->is_active) {
                return redirect()->route('login')
                    ->withErrors(['email' => 'Akun Anda telah dinonaktifkan.']);
            }

            Auth::login($user, true);
            request()->session()->regenerate();
            $cartService->mergeSessionToDatabase($user);

            return redirect()->intended($user->getDashboardUrl())
                ->with('success', 'Berhasil masuk dengan Google!');
        } catch (\Throwable $e) {
            Log::error('Google OAuth callback error: ' . $e->getMessage());

            return redirect()->route('login')
                ->withErrors(['email' => 'Gagal login dengan Google. Silakan coba lagi.']);
        }
    }
}
