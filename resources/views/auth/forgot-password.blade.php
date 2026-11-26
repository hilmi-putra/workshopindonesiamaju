@extends('layouts.auth')

@section('title', 'Lupa Kata Sandi | Mega Press')

@section('content')
<div class="bg-white p-8 sm:p-12 rounded-2xl shadow-xl w-full">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-light tracking-wide text-gray-800">LUPA KATA SANDI</h1>
        <p class="text-xs text-gray-500 mt-2">Masukkan email terdaftar untuk menerima tautan reset kata sandi.</p>
    </div>

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <x-input type="email" name="email" placeholder="Alamat email Anda" required autofocus variant="underline" />

        <button type="submit"
                class="w-full h-12 bg-[#DB8B00] hover:bg-[#b06d00] text-white font-bold tracking-wider text-sm transition-colors cursor-pointer">
            KIRIM TAUTAN RESET
        </button>

        <div class="text-center pt-2">
            <a href="{{ route('login') }}" class="text-xs text-gray-500 hover:text-[#DB8B00]">Kembali ke Login</a>
        </div>
    </form>
</div>
@endsection
