@extends('layouts.auth')

@section('title', 'Login | Workshop Indonesia Maju')

@section('content')
<div class="bg-white p-8 sm:p-12 rounded-2xl shadow-xl w-full">
    <div class="text-center mb-10">
        <h1 class="text-2xl sm:text-3xl font-light tracking-wide text-gray-800">
            LOGIN <span class="text-gray-300 mx-1">/</span>
            <a href="{{ route('register') }}" class="text-gray-400 hover:text-gray-600 transition-colors">REGISTER</a>
        </h1>
    </div>

    <form method="POST" action="{{ route('login.post') }}" class="space-y-7">
        @csrf

        <x-input type="email" name="email" placeholder="Email address" required autofocus variant="underline" />

        <div>
            <div class="flex justify-end mb-1">
                <a href="{{ route('password.request') }}" class="text-xs text-gray-400 hover:text-[#DB8B00] transition-colors">
                    Lupa kata sandi?
                </a>
            </div>
            <x-input type="password" name="password" placeholder="Password" required variant="underline" />
        </div>

        <label class="flex items-center gap-3 text-sm text-gray-500 cursor-pointer pt-1">
            <input type="checkbox" name="remember" class="rounded-sm border-gray-300 text-[#DB8B00] focus:ring-[#DB8B00]">
            <span>Ingat saya</span>
        </label>

        <div class="pt-3 space-y-4">
            <button type="submit"
                    class="w-full h-12 bg-[#DB8B00] hover:bg-[#b06d00] text-white font-bold tracking-wider text-sm transition-colors cursor-pointer">
                LOGIN
            </button>

            <div class="relative flex items-center py-1">
                <div class="flex-grow border-t border-gray-200"></div>
                <span class="shrink-0 mx-4 text-gray-400 text-xs uppercase">atau</span>
                <div class="flex-grow border-t border-gray-200"></div>
            </div>

            <a href="{{ route('auth.google') }}"
               class="w-full h-12 border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold tracking-wider text-sm transition-colors flex items-center justify-center gap-3">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                LOGIN WITH GOOGLE
            </a>
        </div>
    </form>
</div>
@endsection
