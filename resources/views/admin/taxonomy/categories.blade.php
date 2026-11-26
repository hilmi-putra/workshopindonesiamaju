@extends('layouts.admin')

@section('title', 'Kategori & Genre | Admin WIM')
@section('breadcrumb', 'Kategori & Genre')
@section('page_title', 'Kategori & Genre Buku')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
    {{-- Categories --}}
    <div class="space-y-5">
        <h2 class="font-bold text-base">Kategori Buku</h2>
        <form method="POST" action="{{ route('admin.categories.store') }}" class="flex gap-2">
            @csrf
            <input type="text" name="name" placeholder="Nama kategori baru..." required class="flex-1 h-10 rounded-xl border border-[#D9DFEE] px-3 text-sm">
            <x-button type="submit" variant="primary" size="sm">Tambah</x-button>
        </form>
        <div class="border border-[#E3E7F3] rounded-2xl divide-y divide-gray-100">
            @foreach($categories as $cat)
                <div class="p-3.5 flex justify-between items-center text-sm">
                    <div>
                        <span class="font-semibold">{{ $cat->name }}</span>
                        <span class="text-xs text-gray-400 ml-2">({{ $cat->books_count }} buku)</span>
                    </div>
                    <form method="POST"
                          action="{{ route('admin.categories.destroy', $cat) }}"
                          data-confirm
                          data-confirm-title="Hapus Kategori?"
                          data-confirm-message="Kategori buku ini akan dihapus secara permanen."
                          data-confirm-item="{{ $cat->name }}"
                          data-confirm-button="Ya, Hapus Kategori"
                          data-confirm-variant="danger">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs font-bold text-red-600 hover:underline cursor-pointer">Hapus</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Genres --}}
    <div class="space-y-5">
        <h2 class="font-bold text-base">Genre Buku</h2>
        <form method="POST" action="{{ route('admin.genres.store') }}" class="flex gap-2">
            @csrf
            <input type="text" name="name" placeholder="Nama genre baru..." required class="flex-1 h-10 rounded-xl border border-[#D9DFEE] px-3 text-sm">
            <x-button type="submit" variant="primary" size="sm">Tambah</x-button>
        </form>
        <div class="border border-[#E3E7F3] rounded-2xl divide-y divide-gray-100">
            @foreach($genres as $gen)
                <div class="p-3.5 flex justify-between items-center text-sm">
                    <div>
                        <span class="font-semibold">{{ $gen->name }}</span>
                        <span class="text-xs text-gray-400 ml-2">({{ $gen->books_count }} buku)</span>
                    </div>
                    <form method="POST"
                          action="{{ route('admin.genres.destroy', $gen) }}"
                          data-confirm
                          data-confirm-title="Hapus Genre?"
                          data-confirm-message="Genre buku ini akan dihapus secara permanen."
                          data-confirm-item="{{ $gen->name }}"
                          data-confirm-button="Ya, Hapus Genre"
                          data-confirm-variant="danger">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs font-bold text-red-600 hover:underline cursor-pointer">Hapus</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
