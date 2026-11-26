@extends('layouts.app')

@section('title', ($book->meta_title ?: $book->title) . ' | WIM Bookstore')
@section('meta_description', $book->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($book->short_description ?: $book->long_description), 160))

@section('meta')
    <meta property="og:title" content="{{ $book->title }}">
    <meta property="og:image" content="{{ $book->cover_url }}">
@endsection

@section('content')
<div class="flex flex-col bg-[#F3F4F7] text-[#111218] min-h-screen pt-20 sm:pt-28 md:pt-36">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 w-full">
        {{-- Breadcrumb Top Hairline Bar --}}
        <div class="flex items-center justify-between border-t border-[#111218]/40 pt-3 pb-8 text-[11px] font-bold uppercase tracking-[0.16em] text-[#636778]">
            <div class="flex items-center gap-1.5 flex-wrap">
                <a href="{{ route('home') }}" class="hover:text-[#2B3288]">Home</a>
                <span>/</span>
                <a href="{{ route('bookstore.index') }}" class="hover:text-[#2B3288]">Bookstore</a>
                <span>/</span>
                <span class="text-[#2B3288]">{{ $book->title }}</span>
            </div>
            <span class="hidden sm:inline">WIM Catalogue</span>
        </div>

        {{-- Main Product Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-start">
            {{-- Left Column: Gallery --}}
            <div x-data="{ activeImg: '{{ $book->cover_url }}' }" class="lg:col-span-5 w-full space-y-3">
                <div class="relative aspect-[4/5] bg-[#EAECEF] border border-[#C9CEDF] flex items-center justify-center p-8 overflow-hidden">
                    <div class="absolute inset-x-[18%] top-[10%] bottom-[8%] bg-[#DDE2F3] border border-[#2B3288]/20 rotate-2"></div>
                    <img :src="activeImg" alt="{{ $book->title }}" class="relative z-10 max-h-full w-auto object-contain border border-[#111218]/15 shadow-md bg-white">
                </div>

                @if(is_array($book->image_gallery) && count($book->image_gallery) > 0)
                    <div class="flex gap-2.5 overflow-x-auto pb-1">
                        <button type="button" @click="activeImg = '{{ $book->cover_url }}'"
                                class="w-16 h-20 border border-[#C9CEDF] hover:border-[#2B3288] overflow-hidden shrink-0 cursor-pointer bg-white">
                            <img src="{{ $book->cover_url }}" alt="Cover" class="w-full h-full object-cover">
                        </button>
                        @foreach($book->image_gallery as $img)
                            @if($img)
                                <button type="button" @click="activeImg = '{{ $img }}'"
                                        class="w-16 h-20 border border-[#C9CEDF] hover:border-[#2B3288] overflow-hidden shrink-0 cursor-pointer bg-white">
                                    <img src="{{ $img }}" alt="Preview" class="w-full h-full object-cover">
                                </button>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Right Column: Book Info & Actions --}}
            <div class="lg:col-span-7 w-full flex flex-col font-sans text-sm bg-white border border-[#C9CEDF] p-6 sm:p-10">
                <p class="text-[11px] uppercase tracking-[0.2em] font-bold text-[#2B3288] mb-2">
                    Oleh {{ $book->author?->name ?? 'Workshop Indonesia Maju' }}
                </p>

                <h1 class="font-serif text-3xl sm:text-5xl text-[#111218] leading-[1.04] tracking-[-0.03em] mb-2">
                    {{ $book->title }}
                </h1>
                @if($book->subtitle)
                    <p class="text-base text-[#636778] mb-4">{{ $book->subtitle }}</p>
                @endif

                <div class="flex flex-wrap items-baseline gap-3 py-5 my-5 border-y border-[#C9CEDF]">
                    <span class="text-[#2B3288] text-3xl font-extrabold tracking-tight">
                        Rp {{ number_format((float)$book->final_price, 0, ',', '.') }}
                    </span>
                    @if((float)$book->final_price < (float)$book->price)
                        <span class="line-through text-[#636778] text-sm">
                            Rp {{ number_format((float)$book->price, 0, ',', '.') }}
                        </span>
                    @endif
                    <span class="ml-auto text-[11px] font-bold uppercase tracking-wider px-3 py-1 border {{ $book->stock > 0 ? 'bg-[#E7EBF7] text-[#2B3288] border-[#2B3288]/25' : 'bg-red-50 text-red-700 border-red-200' }}">
                        {{ $book->stock > 0 ? "Tersedia: {$book->stock} eks" : 'Stok Habis' }}
                    </span>
                </div>

                <div class="text-[#636778] leading-relaxed mb-7 text-sm">
                    {!! $book->short_description ?: \Illuminate\Support\Str::limit(strip_tags($book->long_description ?? ''), 280) !!}
                </div>

                {{-- Livewire Cart & Direct WA Checkout --}}
                <livewire:book-actions :book="$book" />

                {{-- Wishlist Toggle --}}
                <div class="flex items-center gap-6 mb-7">
                    <livewire:wishlist-toggle :book-id="$book->id" />
                </div>

                {{-- Architectural Key-Value Metadata Ledger --}}
                <div class="border-t border-[#111218]/25 divide-y divide-[#C9CEDF] text-xs">
                    <div class="py-2.5 flex justify-between gap-4">
                        <span class="font-bold uppercase tracking-wider text-[#636778]">ISBN</span>
                        <span class="font-semibold text-[#111218]">{{ $book->isbn ?: '-' }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between gap-4">
                        <span class="font-bold uppercase tracking-wider text-[#636778]">Penerbit</span>
                        <span class="font-semibold text-[#111218]">{{ $book->publisher?->name ?: 'Workshop Indonesia Maju' }}</span>
                    </div>
                    @if($book->categories->isNotEmpty())
                        <div class="py-2.5 flex justify-between gap-4">
                            <span class="font-bold uppercase tracking-wider text-[#636778]">Kategori</span>
                            <span class="font-semibold text-[#2B3288] text-right">{{ $book->categories->pluck('name')->implode(', ') }}</span>
                        </div>
                    @endif
                    @if($book->genres->isNotEmpty())
                        <div class="py-2.5 flex justify-between gap-4">
                            <span class="font-bold uppercase tracking-wider text-[#636778]">Genre</span>
                            <span class="font-semibold text-[#111218] text-right">{{ $book->genres->pluck('name')->implode(', ') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Metadata Tabs (Description, Specs, Reviews) --}}
        <div x-data="{ tab: 'description' }" class="mt-16 bg-white border border-[#C9CEDF] p-6 sm:p-10">
            <div class="flex justify-center border-b border-[#C9CEDF] gap-8 mb-10">
                <button type="button" @click="tab = 'description'"
                        :class="tab === 'description' ? 'text-[#2B3288] border-[#2B3288]' : 'text-[#636778] border-transparent hover:text-[#111218]'"
                        class="pb-4 text-xs font-bold tracking-[0.16em] uppercase border-b-2 transition-colors cursor-pointer">
                    Deskripsi &amp; Spesifikasi
                </button>
                <button type="button" @click="tab = 'reviews'"
                        :class="tab === 'reviews' ? 'text-[#2B3288] border-[#2B3288]' : 'text-[#636778] border-transparent hover:text-[#111218]'"
                        class="pb-4 text-xs font-bold tracking-[0.16em] uppercase border-b-2 transition-colors cursor-pointer">
                    Ulasan ({{ $book->reviews->count() }})
                </button>
            </div>

            <div x-show="tab === 'description'" class="space-y-10">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-sm text-[#636778]">
                    <div>
                        <h4 class="font-bold tracking-[0.16em] uppercase text-xs text-[#2B3288] pb-2 border-b border-[#C9CEDF] mb-3">Pengantar Redaksi</h4>
                        <div class="leading-relaxed">{!! $book->editor_note ?: '<p class="text-gray-400 italic">Tidak ada pengantar redaksi.</p>' !!}</div>
                    </div>
                    <div>
                        <h4 class="font-bold tracking-[0.16em] uppercase text-xs text-[#2B3288] pb-2 border-b border-[#C9CEDF] mb-3">Spesifikasi Fisik</h4>
                        <ul class="space-y-2">
                            <li><strong class="text-[#111218]">Format Cover:</strong> <span class="capitalize">{{ $book->cover_type ?? '-' }}</span></li>
                            <li><strong class="text-[#111218]">Jumlah Halaman:</strong> {{ $book->page_count ? $book->page_count . ' Halaman' : '-' }}</li>
                            <li><strong class="text-[#111218]">Dimensi:</strong> {{ $book->dimensions ?: '-' }}</li>
                            <li><strong class="text-[#111218]">Berat:</strong> {{ $book->weight ? $book->weight . ' gr' : '-' }}</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold tracking-[0.16em] uppercase text-xs text-[#2B3288] pb-2 border-b border-[#C9CEDF] mb-3">Detail Penerbitan</h4>
                        <ul class="space-y-2">
                            <li><strong class="text-[#111218]">Penerbit:</strong> {{ $book->publisher?->name ?: '-' }}</li>
                            <li><strong class="text-[#111218]">ISBN:</strong> {{ $book->isbn ?: '-' }}</li>
                            <li><strong class="text-[#111218]">Tanggal Terbit:</strong> {{ optional($book->published_at)->translatedFormat('d F Y') ?: '-' }}</li>
                        </ul>
                    </div>
                </div>

                @if($book->long_description)
                    <div class="pt-8 border-t border-[#C9CEDF]">
                        <h4 class="font-bold tracking-[0.16em] uppercase text-xs text-[#2B3288] mb-4">Sinopsis / Deskripsi Lengkap</h4>
                        <div class="text-sm text-[#111218]/85 leading-relaxed space-y-3">{!! $book->long_description !!}</div>
                    </div>
                @endif
            </div>

            <div x-show="tab === 'reviews'" x-cloak class="max-w-3xl mx-auto">
                @forelse($book->reviews as $review)
                    <div class="py-5 border-b border-[#C9CEDF] last:border-0">
                        <div class="flex justify-between items-center mb-1.5">
                            <span class="font-bold text-sm text-[#111218]">{{ $review->user?->name ?? 'Pembaca' }}</span>
                            <span class="text-xs font-bold text-[#2B3288]">{{ $review->rating }} / 5 ★</span>
                        </div>
                        <p class="text-sm text-[#636778]">{{ $review->comment }}</p>
                        @if($review->reply)
                            <p class="mt-2.5 text-xs bg-[#F3F4F7] p-3 border-l-2 border-[#2B3288]">
                                <strong class="text-[#2B3288]">Balasan Admin:</strong> {{ $review->reply }}
                            </p>
                        @endif
                    </div>
                @empty
                    <p class="text-center py-10 text-[#636778] text-sm">Belum ada ulasan untuk buku ini.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Related Books --}}
    @if($relatedBooks->isNotEmpty())
        <div class="bg-[#EAECEF] border-t border-[#C9CEDF] mt-16 pt-14 pb-24">
            <div class="max-w-6xl mx-auto px-4 sm:px-6">
                <div class="flex items-center justify-between border-t border-[#111218]/40 pt-3 pb-8 text-xs font-bold uppercase tracking-[0.18em]">
                    <span class="text-[#111218]">Buku Terkait</span>
                    <span class="text-[#2B3288]">Rekomendasi Serupa</span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
                    @foreach($relatedBooks as $rel)
                        <x-book-card :book="$rel" />
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
