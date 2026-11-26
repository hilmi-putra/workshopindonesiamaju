@extends('layouts.dashboard')

@section('title', 'Profil Saya | Workshop Indonesia Maju')

@section('dashboard_content')
<div class="bg-white rounded-2xl border border-[#DDD8C8] overflow-hidden">
    <div class="p-6 md:p-8 border-b border-gray-100">
        <h1 class="text-2xl font-black text-[#171512] uppercase tracking-tighter mb-1">Profil &amp; Alamat</h1>
        <p class="text-sm text-gray-500">Data ini otomatis mengisi formulir checkout WhatsApp Anda.</p>
    </div>

    <div class="p-6 md:p-8 grid grid-cols-1 lg:grid-cols-2 gap-10">
        <form method="POST" action="{{ route('dashboard.profile.update') }}" class="space-y-5">
            @csrf
            @method('PUT')
            <h2 class="font-bold text-sm uppercase tracking-wider text-[#171512]">Info Personal</h2>

            <x-input name="name" label="Nama Lengkap" :value="$user->name" required />
            <x-input type="email" name="email_display" label="Email" :value="$user->email" disabled />
            <x-input name="phone" label="Nomor WhatsApp / HP" :value="$user->phone" placeholder="0812xxxxxxxx" />

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-[#171512] mb-1.5">Alamat Pengiriman Default</label>
                <textarea name="address" rows="3" placeholder="Jalan, Kecamatan, Kota, Kode Pos"
                          class="w-full rounded-xl border border-[#DDD8C8] bg-white px-4 py-2.5 text-sm">{{ old('address', $user->address) }}</textarea>
            </div>

            <x-button type="submit" variant="primary">Simpan Profil</x-button>
        </form>

        <form method="POST" action="{{ route('dashboard.password.update') }}" class="space-y-5">
            @csrf
            @method('PUT')
            <h2 class="font-bold text-sm uppercase tracking-wider text-[#171512]">Ubah Kata Sandi</h2>

            <x-input type="password" name="current_password" label="Kata Sandi Saat Ini" required />
            <x-input type="password" name="password" label="Kata Sandi Baru" required />
            <x-input type="password" name="password_confirmation" label="Konfirmasi Kata Sandi Baru" required />

            <x-button type="submit" variant="dark">Perbarui Kata Sandi</x-button>
        </form>
    </div>
</div>
@endsection
