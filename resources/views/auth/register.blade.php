@extends('layouts.auth')

@section('title', 'Daftar Akun | Workshop Indonesia Maju')

@section('content')
<div class="bg-white p-8 sm:p-12 rounded-2xl shadow-xl w-full">
    <div class="text-center mb-8">
        <h1 class="text-2xl sm:text-3xl font-light tracking-wide text-gray-800">
            <a href="{{ route('login') }}" class="text-gray-400 hover:text-gray-600 transition-colors">LOGIN</a>
            <span class="text-gray-300 mx-1">/</span> REGISTER
        </h1>
    </div>

    <form method="POST" action="{{ route('register.post') }}" class="space-y-6">
        @csrf

        <x-input type="text" name="name" placeholder="Nama lengkap" required autofocus variant="underline" />
        <x-input type="email" name="email" placeholder="Alamat email" required variant="underline" />
        <x-input type="tel" name="phone" placeholder="Nomor WhatsApp (opsional, mis. 0812xxx)" variant="underline" />
        <x-input type="password" name="password" placeholder="Kata sandi (minimal 8 karakter)" required variant="underline" />
        <x-input type="password" name="password_confirmation" placeholder="Ulangi kata sandi" required variant="underline" />

        <div class="pt-3 space-y-4">
            <button type="submit"
                    class="w-full h-12 bg-[#DB8B00] hover:bg-[#b06d00] text-white font-bold tracking-wider text-sm transition-colors cursor-pointer">
                REGISTER
            </button>

            <a href="{{ route('auth.google') }}"
               class="w-full h-12 border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold tracking-wider text-sm transition-colors flex items-center justify-center gap-3">
                DAFTAR DENGAN GOOGLE
            </a>
        </div>
    </form>
</div>
@endsection
