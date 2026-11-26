@extends('layouts.app')

@section('title', ($book->meta_title ?: $book->title) . ' | Mega Bookstore')
@section('meta_description', $book->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($book->short_description ?: $book->long_description), 160))

@section('meta')
    <meta property="og:title" content="{{ $book->title }}">
    <meta property="og:image" content="{{ $book->cover_url }}">
@endsection

@section('content')
<div class="flex flex-col bg-white min-h-screen pt-28 md:pt-36">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 w-full">
        {{-- Main Product Grid (Matches Next.js ProductDetailPage) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-start">
            {{-- Left Column: Gallery --}}
            <div x-data="{ activeImg: '{{ $book->cover_url }}' }" class="w-full space-y-4">
                <div class="aspect-[4/5] bg-[#FAF8F4] rounded-2xl border border-[#DDD8C8] flex items-center justify-center p-8 overflow-hidden">
                    <img :src="activeImg" alt="{{ $book->title }}" class="max-h-full w-auto object-contain shadow-xl rounded-xs">
                </div>

                @if(is_array($book->image_gallery) && count($book->image_gallery) > 0)
                    <div class="flex gap-3 overflow-x-auto pb-1">
                        <button type="button" @click="activeImg = '{{ $book->cover_url }}'"
                                class="w-16 h-20 rounded-lg border border-[#DDD8C8] overflow-hidden shrink-0 cursor-pointer">
                            <img src="{{ $book->cover_url }}" alt="Cover" class="w-full h-full object-cover">
                        </button>
                        @foreach($book->image_gallery as $img)
                            @if($img)
                                <button type="button" @click="activeImg = '{{ $img }}'"
                                        class="w-16 h-20 rounded-lg border border-[#DDD8C8] overflow-hidden shrink-0 cursor-pointer">
                                    <img src="{{ $img }}" alt="Preview" class="w-full h-full object-cover">
                                </button>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Right Column: Book Info & Actions --}}
            <div class="w-full flex flex-col font-sans text-sm">
                <div class="flex items-center gap-1.5 flex-wrap mb-6 text-gray-500 text-xs tracking-wider uppercase">
                    <a href="{{ route('home') }}" class="hover:text-black">Home</a>
                    <span>/</span>
                    <a href="{{ route('bookstore.index') }}" class="hover:text-black">Bookstore</a>
                    <span>/</span>
                    <span class="font-semibold text-black">{{ $book->title }}</span>
                </div>

                <h1 class="font-bold text-3xl sm:text-4xl text-[#171512] mb-2">{{ $book->title }}</h1>
                @if($book->subtitle)
                    <p class="text-base text-gray-500 mb-4">{{ $book->subtitle }}</p>
                @endif
                <p class="text-xs uppercase tracking-widest font-bold text-[#DB8B00] mb-4">
                    Oleh {{ $book->author?->name ?? 'Mega Press' }}
                </p>

                <div class="flex items-baseline gap-3 mb-6">
                    <span class="text-[#084c3c] text-2xl sm:text-3xl font-bold">
                        Rp {{ number_format((float)$book->final_price, 0, ',', '.') }}
                    </span>
                    @if((float)$book->final_price < (float)$book->price)
                        <span class="line-through text-gray-400 text-sm">
                            Rp {{ number_format((float)$book->price, 0, ',', '.') }}
                        </span>
                    @endif
                    <span class="ml-auto text-xs font-semibold px-3 py-1 rounded-full {{ $book->stock > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                        {{ $book->stock > 0 ? "Tersedia: {$book->stock} eks" : 'Stok Habis' }}
                    </span>
                </div>

                <div class="text-gray-600 leading-relaxed mb-7 text-sm">
                    {!! $book->short_description ?: \Illuminate\Support\Str::limit(strip_tags($book->long_description ?? ''), 280) !!}
                </div>

                {{-- Livewire Cart & Direct WA Checkout --}}
                <livewire:book-actions :book="$book" />

                {{-- Wishlist Toggle --}}
                <div class="flex items-center gap-6 mb-7">
                    <livewire:wishlist-toggle :book-id="$book->id" />
                </div>

                <div class="border-t border-gray-100 pt-5 space-y-2 text-xs text-gray-600">
                    <div><strong class="text-black">ISBN:</strong> {{ $book->isbn ?: '-' }}</div>
                    <div><strong class="text-black">Penerbit:</strong> {{ $book->publisher?->name ?: 'Mega Press Nusantara' }}</div>
                    @if($book->categories->isNotEmpty())
                        <div>
                            <strong class="text-black">Kategori:</strong>
                            {{ $book->categories->pluck('name')->implode(', ') }}
                        </div>
                    @endif
                    @if($book->genres->isNotEmpty())
                        <div>
                            <strong class="text-black">Genre:</strong>
                            {{ $book->genres->pluck('name')->implode(', ') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Metadata Tabs (Description, Specs, Reviews) --}}
        <div x-data="{ tab: 'description' }" class="mt-20">
            <div class="flex justify-center border-b border-gray-100 gap-8 mb-12">
                <button type="button" @click="tab = 'description'"
                        :class="tab === 'description' ? 'text-[#171512] border-[#084c3c]' : 'text-gray-500 border-transparent'"
                        class="pb-4 text-xs font-bold tracking-widest uppercase border-b-2 transition-colors cursor-pointer">
                    Deskripsi &amp; Spesifikasi
                </button>
                <button type="button" @click="tab = 'reviews'"
                        :class="tab === 'reviews' ? 'text-[#171512] border-[#084c3c]' : 'text-gray-500 border-transparent'"
                        class="pb-4 text-xs font-bold tracking-widest uppercase border-b-2 transition-colors cursor-pointer">
                    Ulasan ({{ $book->reviews->count() }})
                </button>
            </div>

            <div x-show="tab === 'description'" class="space-y-10">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10 text-sm text-gray-600">
                    <div>
                        <h4 class="font-bold tracking-widest uppercase text-xs text-[#171512] mb-3">Pengantar Redaksi</h4>
                        <div class="leading-relaxed">{!! $book->editor_note ?: '<p class="text-gray-400 italic">Tidak ada pengantar redaksi.</p>' !!}</div>
                    </div>
                    <div>
                        <h4 class="font-bold tracking-widest uppercase text-xs text-[#171512] mb-3">Spesifikasi Fisik</h4>
                        <ul class="space-y-2">
                            <li><strong>Format Cover:</strong> <span class="capitalize">{{ $book->cover_type ?? '-' }}</span></li>
                            <li><strong>Jumlah Halaman:</strong> {{ $book->page_count ? $book->page_count . ' Halaman' : '-' }}</li>
                            <li><strong>Dimensi:</strong> {{ $book->dimensions ?: '-' }}</li>
                            <li><strong>Berat:</strong> {{ $book->weight ? $book->weight . ' gr' : '-' }}</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold tracking-widest uppercase text-xs text-[#171512] mb-3">Detail Penerbitan</h4>
                        <ul class="space-y-2">
                            <li><strong>Penerbit:</strong> {{ $book->publisher?->name ?: '-' }}</li>
                            <li><strong>ISBN:</strong> {{ $book->isbn ?: '-' }}</li>
                            <li><strong>Tanggal Terbit:</strong> {{ optional($book->published_at)->translatedFormat('d F Y') ?: '-' }}</li>
                        </ul>
                    </div>
                </div>

                @if($book->long_description)
                    <div class="pt-8 border-t border-gray-100">
                        <h4 class="font-bold tracking-widest uppercase text-xs text-[#171512] mb-4">Sinopsis / Deskripsi Lengkap</h4>
                        <div class="text-sm text-gray-600 leading-relaxed space-y-3">{!! $book->long_description !!}</div>
                    </div>
                @endif
            </div>

            <div x-show="tab === 'reviews'" x-cloak class="max-w-3xl mx-auto">
                @forelse($book->reviews as $review)
                    <div class="py-5 border-b border-gray-100">
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-bold text-sm text-[#171512]">{{ $review->user?->name ?? 'Pembaca' }}</span>
                            <span class="text-xs font-bold text-[#DB8B00]">{{ $review->rating }} / 5 ★</span>
                        </div>
                        <p class="text-sm text-gray-600">{{ $review->comment }}</p>
                        @if($review->reply)
                            <p class="mt-2 text-xs bg-[#FAF8F4] p-3 rounded-lg border border-[#DDD8C8]"><strong>Balasan Admin:</strong> {{ $review->reply }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-center py-10 text-gray-500 text-sm">Belum ada ulasan untuk buku ini.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Related Books --}}
    @if($relatedBooks->isNotEmpty())
        <div class="bg-[#FAF7F3] mt-20 pt-16 pb-24">
            <div class="max-w-6xl mx-auto px-4 sm:px-6">
                <div class="mb-10">
                    <h3 class="font-sans font-bold tracking-widest uppercase text-lg text-[#171512] mb-2">Buku Terkait</h3>
                    <div class="h-[2px] w-16 bg-[#084c3c]"></div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    @foreach($relatedBooks as $rel)
                        <x-book-card :book="$rel" />
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
