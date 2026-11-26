@extends('layouts.admin')

@section('title', 'Ulasan Pembaca | Admin Mega Press')
@section('breadcrumb', 'Ulasan')
@section('page_title', 'Moderasi Ulasan Buku')

@section('content')
<div class="space-y-5">
    <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari komentar atau judul buku..." class="flex-1 h-10 rounded-xl border border-gray-200 px-4 text-sm">
        <button type="submit" class="px-5 h-10 rounded-xl bg-[#171512] text-white text-xs font-bold uppercase cursor-pointer">Cari</button>
    </form>

    <div class="space-y-4">
        @forelse($reviews as $review)
            <div class="border border-gray-200 rounded-2xl p-5 space-y-3 bg-white">
                <div class="flex flex-wrap justify-between items-start gap-4">
                    <div>
                        <p class="font-bold text-sm">{{ $review->book?->title ?? 'Buku' }}</p>
                        <p class="text-xs text-gray-500">Oleh {{ $review->user?->name ?? 'Pembaca' }} &middot; Rating: <strong class="text-[#DB8B00]">{{ $review->rating }}/5</strong></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <form method="POST" action="{{ route('admin.reviews.toggle-hide', $review) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-bold cursor-pointer">
                                {{ $review->is_hidden ? 'Tampilkan' : 'Sembunyikan' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Hapus ulasan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 rounded-lg border border-red-200 text-red-600 text-xs font-bold cursor-pointer">Hapus</button>
                        </form>
                    </div>
                </div>
                <p class="text-sm text-gray-700">{{ $review->comment }}</p>
                <form method="POST" action="{{ route('admin.reviews.reply', $review) }}" class="flex gap-2">
                    @csrf
                    <input type="text" name="reply" value="{{ $review->reply }}" placeholder="Tulis balasan admin..." required class="flex-1 h-9 rounded-lg border border-gray-200 px-3 text-xs">
                    <button type="submit" class="px-4 h-9 rounded-lg bg-[#DB8B00] text-white text-xs font-bold cursor-pointer">Balas</button>
                </form>
            </div>
        @empty
            <p class="py-10 text-center text-gray-400">Belum ada ulasan buku.</p>
        @endforelse
    </div>
    <div>{{ $reviews->links() }}</div>
</div>
@endsection
