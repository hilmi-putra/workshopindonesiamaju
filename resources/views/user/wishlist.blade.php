@extends('layouts.dashboard')

@section('title', 'Wishlist Saya | Workshop Indonesia Maju')

@section('dashboard_content')
<div class="bg-white rounded-2xl border border-[#DDD8C8] overflow-hidden">
    <div class="p-6 md:p-8 border-b border-gray-100">
        <h1 class="text-2xl font-black text-[#171512] uppercase tracking-tighter mb-1">Wishlist Saya</h1>
        <p class="text-sm text-gray-500">Buku-buku yang Anda simpan untuk dibeli nanti.</p>
    </div>

    <div class="p-6 md:p-8">
        @forelse($wishlists as $wish)
            @if($wish->book)
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 py-5 border-b border-gray-100 last:border-0">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('bookstore.show', $wish->book->slug) }}" class="w-16 h-24 bg-[#F0EBE1] rounded-sm overflow-hidden shrink-0">
                            <img src="{{ $wish->book->cover_url }}" alt="{{ $wish->book->title }}" class="w-full h-full object-cover">
                        </a>
                        <div>
                            <a href="{{ route('bookstore.show', $wish->book->slug) }}" class="font-serif text-xl text-[#171512] hover:text-[#DB8B00]">
                                {{ $wish->book->title }}
                            </a>
                            <p class="text-xs text-gray-500">{{ $wish->book->author?->name ?? 'Workshop Indonesia Maju' }}</p>
                            <p class="text-sm font-bold text-[#DB8B00] mt-1">Rp {{ number_format((float)$wish->book->final_price, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('bookstore.show', $wish->book->slug) }}"
                           class="px-5 py-2.5 bg-[#171512] hover:bg-[#DB8B00] text-white text-xs font-bold uppercase tracking-wider transition-colors">
                            Lihat &amp; Beli
                        </a>
                        <form method="POST"
                              action="{{ route('dashboard.wishlist.remove', $wish->book->id) }}"
                              data-confirm
                              data-confirm-title="Hapus dari Wishlist?"
                              data-confirm-message="Buku ini akan dihapus dari daftar wishlist Anda."
                              data-confirm-item="{{ $wish->book->title }}"
                              data-confirm-button="Ya, Hapus"
                              data-confirm-variant="danger">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2.5 border border-red-200 text-red-600 hover:bg-red-50 text-xs font-bold uppercase tracking-wider cursor-pointer">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        @empty
            <div class="py-16 text-center bg-gray-50/50 rounded-xl border border-dashed border-gray-200">
                <p class="text-gray-500 font-medium mb-4">Wishlist Anda masih kosong.</p>
                <a href="{{ route('bookstore.index') }}" class="inline-block px-7 py-3 bg-[#DB8B00] text-white rounded-full text-xs font-bold uppercase tracking-widest">
                    Jelajahi Bookstore
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
