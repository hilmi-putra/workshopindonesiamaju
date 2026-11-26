@extends('layouts.admin')

@section('title', 'Penerbit | Admin Mega Press')
@section('breadcrumb', 'Penerbit')
@section('page_title', 'Manajemen Penerbit')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <form method="POST" action="{{ route('admin.publishers.store') }}" class="lg:col-span-4 space-y-4 bg-[#FAF8F4] p-6 rounded-2xl border border-gray-100 h-fit">
        @csrf
        <h2 class="font-bold text-base">Tambah Penerbit</h2>
        <x-input name="name" label="Nama Penerbit" required />
        <x-input type="email" name="email" label="Email" />
        <x-input name="phone" label="Telepon" />
        <x-input name="address" label="Alamat" />
        <x-button type="submit" variant="primary" class="w-full">Simpan Penerbit</x-button>
    </form>

    <div class="lg:col-span-8 space-y-4">
        <div class="border border-gray-100 rounded-2xl divide-y divide-gray-100">
            @foreach($publishers as $pub)
                <div class="p-4 flex justify-between items-center text-sm">
                    <div>
                        <p class="font-bold">{{ $pub->name }}</p>
                        <p class="text-xs text-gray-500">{{ $pub->books_count }} buku &middot; {{ $pub->phone ?: '-' }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.publishers.destroy', $pub) }}" onsubmit="return confirm('Hapus penerbit ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs font-bold text-red-600 hover:underline cursor-pointer">Hapus</button>
                    </form>
                </div>
            @endforeach
        </div>
        <div>{{ $publishers->links() }}</div>
    </div>
</div>
@endsection
