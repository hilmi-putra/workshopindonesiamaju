@extends('layouts.auth')

@section('title', 'Buat Kata Sandi Baru | Mega Press')

@section('content')
<div class="bg-white p-8 sm:p-12 rounded-2xl shadow-xl w-full">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-light tracking-wide text-gray-800">RESET KATA SANDI</h1>
    </div>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <x-input type="email" name="email" :value="$email" placeholder="Alamat email" required variant="underline" />
        <x-input type="password" name="password" placeholder="Kata sandi baru" required variant="underline" />
        <x-input type="password" name="password_confirmation" placeholder="Konfirmasi kata sandi baru" required variant="underline" />

        <button type="submit"
                class="w-full h-12 bg-[#DB8B00] hover:bg-[#b06d00] text-white font-bold tracking-wider text-sm transition-colors cursor-pointer">
            SIMPAN KATA SANDI
        </button>
    </form>
</div>
@endsection
