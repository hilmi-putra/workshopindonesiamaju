@extends('layouts.admin')

@section('title', 'Penerbit | Admin WIM')
@section('breadcrumb', 'Penerbit')
@section('page_title', 'Manajemen Penerbit')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <form method="POST" action="{{ route('admin.publishers.store') }}" class="lg:col-span-4 space-y-4 bg-[#F5F7FC] p-6 rounded-2xl border border-[#E3E7F3] h-fit">
        @csrf
        <h2 class="font-bold text-base">Tambah Penerbit</h2>
        <x-input name="name" label="Nama Penerbit" required />
        <x-input type="email" name="email" label="Email" />
        <x-input name="phone" label="Telepon" />
        <x-input name="address" label="Alamat" />
        <x-button type="submit" variant="primary" class="w-full">Simpan Penerbit</x-button>
    </form>

    <div class="lg:col-span-8 space-y-4">
        <div class="border border-[#E3E7F3] rounded-2xl divide-y divide-gray-100">
            @foreach($publishers as $pub)
                <div class="p-4 flex justify-between items-center text-sm">
                    <div>
                        <p class="font-bold">{{ $pub->name }}</p>
                        <p class="text-xs text-gray-500">{{ $pub->books_count }} buku &middot; {{ $pub->phone ?: '-' }}</p>
                    </div>
                    <form method="POST"
                          action="{{ route('admin.publishers.destroy', $pub) }}"
                          data-confirm
                          data-confirm-title="Hapus Penerbit?"
                          data-confirm-message="Data penerbit ini akan dihapus secara permanen."
                          data-confirm-item="{{ $pub->name }}"
                          data-confirm-button="Ya, Hapus Penerbit"
                          data-confirm-variant="danger">
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
