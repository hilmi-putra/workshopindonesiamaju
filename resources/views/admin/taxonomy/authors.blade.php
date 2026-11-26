@extends('layouts.admin')

@section('title', 'Penulis Buku | Admin WIM')
@section('breadcrumb', 'Penulis')
@section('page_title', 'Manajemen Penulis')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <form method="POST" action="{{ route('admin.authors.store') }}" class="lg:col-span-4 space-y-4 bg-[#F5F7FC] p-6 rounded-2xl border border-[#E3E7F3] h-fit">
        @csrf
        <h2 class="font-bold text-base">Tambah Penulis</h2>
        <x-input name="name" label="Nama Penulis" required />
        <x-input type="email" name="email" label="Email (Opsional)" />
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider mb-1">Bio Singkat</label>
            <textarea name="bio" rows="3" class="w-full rounded-xl border border-[#D9DFEE] bg-white px-3 py-2 text-sm"></textarea>
        </div>
        <x-button type="submit" variant="primary" class="w-full">Simpan Penulis</x-button>
    </form>

    <div class="lg:col-span-8 space-y-4">
        <div class="border border-[#E3E7F3] rounded-2xl divide-y divide-gray-100">
            @foreach($authors as $author)
                <div class="p-4 flex justify-between items-center text-sm">
                    <div>
                        <p class="font-bold">{{ $author->name }}</p>
                        <p class="text-xs text-gray-500">{{ $author->books_count }} buku &middot; {{ $author->email ?: '-' }}</p>
                    </div>
                    <form method="POST"
                          action="{{ route('admin.authors.destroy', $author) }}"
                          data-confirm
                          data-confirm-title="Hapus Penulis?"
                          data-confirm-message="Data penulis ini akan dihapus secara permanen."
                          data-confirm-item="{{ $author->name }}"
                          data-confirm-button="Ya, Hapus Penulis"
                          data-confirm-variant="danger">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs font-bold text-red-600 hover:underline cursor-pointer">Hapus</button>
                    </form>
                </div>
            @endforeach
        </div>
        <div>{{ $authors->links() }}</div>
    </div>
</div>
@endsection
