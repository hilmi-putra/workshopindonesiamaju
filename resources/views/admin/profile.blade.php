@extends('layouts.admin')

@section('title', 'Profil Admin | Admin Mega Press')
@section('breadcrumb', 'Profil')
@section('page_title', 'Profil & Keamanan Admin')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-10 max-w-4xl">
    <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-4">
        @csrf @method('PUT')
        <h2 class="font-bold text-base">Informasi Akun</h2>
        <x-input name="name" label="Nama" :value="$admin->name" required />
        <x-input name="email_display" label="Email" :value="$admin->email" disabled />
        <x-input name="phone" label="No. HP/WA" :value="$admin->phone" />
        <x-input name="job_title" label="Jabatan" :value="$admin->job_title" />
        <x-button type="submit" variant="primary">Simpan Profil</x-button>
    </form>

    <form method="POST" action="{{ route('admin.profile.password') }}" class="space-y-4">
        @csrf @method('PUT')
        <h2 class="font-bold text-base">Ubah Kata Sandi</h2>
        <x-input type="password" name="current_password" label="Kata Sandi Saat Ini" required />
        <x-input type="password" name="password" label="Kata Sandi Baru" required />
        <x-input type="password" name="password_confirmation" label="Konfirmasi Kata Sandi Baru" required />
        <x-button type="submit" variant="dark">Perbarui Kata Sandi</x-button>
    </form>
</div>
@endsection
