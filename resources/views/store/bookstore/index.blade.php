@extends('layouts.app')

@section('title', 'WIM Bookstore | Katalog Buku Resmi Workshop Indonesia Maju')
@section('meta_description', 'Jelajahi koleksi buku fiksi, non-fiksi, buku ajar, dan referensi ilmiah resmi terbitan Workshop Indonesia Maju.')

@section('content')
<div class="flex flex-col bg-[#F3F4F7] text-[#111218] min-h-screen">
    {{-- Store Hero Section: Architectural Grid + Studio Hardcover Book Artwork --}}
    <section class="pt-20 sm:pt-28 md:pt-36 overflow-hidden border-b border-[#C9CEDF] bg-[#EAECEF] relative">
        <div aria-hidden="true" class="absolute inset-0 pointer-events-none wim-editorial-grid"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pb-12 md:pb-16">
            {{-- Top Micro-Meta Strip --}}
            <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-[0.2em] text-[#636778] border-b border-[#C9CEDF] pt-3 pb-3 mb-8 sm:mb-10">
                <span>WIM Bookstore</span>
                <span class="text-[#2B3288]">Katalog Resmi Penerbit</span>
                <span>Format IDR</span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
                {{-- Left Column --}}
                <div class="lg:col-span-6 flex flex-col items-start relative z-10">
                    <div class="flex items-center text-[52px] sm:text-[72px] md:text-[84px] font-serif font-normal leading-none text-[#111218] tracking-tighter mb-6">
                        <div class="flex flex-col leading-[0.82] mr-3 md:mr-5">
                            <span>B</span>
                            <span>R</span>
                        </div>
                        <div class="flex items-center -mx-2 z-10">
                            <svg width="48" height="114" viewBox="0 0 60 150" class="md:w-[68px] md:h-[164px] rotate-[14deg] z-10" fill="none">
                                <rect x="5" y="5" width="50" height="140" rx="25" stroke="#2B3288" stroke-width="9" />
                            </svg>
                            <svg width="48" height="114" viewBox="0 0 60 150" class="md:w-[68px] md:h-[164px] -ml-5 md:-ml-7 rotate-[-6deg] z-0" fill="none">
                                <rect x="5" y="5" width="50" height="140" rx="25" stroke="#6F77C7" stroke-width="9" />
                            </svg>
                        </div>
                        <div class="flex flex-col leading-[0.82] ml-3 md:ml-5">
                            <span>K</span>
                            <span>M</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 mb-7">
                        <a href="#katalog"
                           class="inline-flex items-center justify-between border border-[#2B3288] px-5 h-11 w-56 bg-[#2B3288] text-white hover:bg-[#1F256D] transition-colors">
                            <span class="font-bold text-xs uppercase tracking-[0.16em]">Mulai Jelajahi</span>
                            <span class="text-sm font-bold">&darr;</span>
                        </a>
                        <span class="text-xs font-bold uppercase tracking-[0.16em] text-[#636778]">Katalog Lengkap</span>
                    </div>

                    <p class="text-base md:text-lg font-medium text-[#111218] leading-relaxed max-w-xl">
                        Temukan <em class="font-serif italic text-[#2B3288] underline decoration-[#2B3288]/50 decoration-1 underline-offset-4">inspirasi bacaan</em> terbaik terbitan Workshop Indonesia Maju untuk menemani keseharian Anda.
                    </p>
                </div>

                {{-- Right Column: Curated Studio Hardcover Volumes Artwork --}}
                <div class="lg:col-span-6 relative z-10">
                    <div class="bg-white border border-[#C9CEDF] overflow-hidden">
                        <div class="aspect-[4/3] w-full overflow-hidden bg-[#EAECEF]">
                            <img src="{{ asset('images/wim-bookstore-hero.jpg') }}"
                                 alt="Koleksi Buku Hardcover WIM Bookstore"
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 border-t border-[#C9CEDF] divide-y sm:divide-y-0 sm:divide-x divide-[#C9CEDF] bg-[#F3F4F7]">
                            @foreach(['Terlaris', 'Fiksi & Novel', 'Non-Fiksi', 'Pilihan'] as $idx => $tagLabel)
                                <a href="#katalog"
                                   class="px-3 py-2.5 flex items-center justify-between text-[11px] font-bold uppercase tracking-wider text-[#111218] hover:bg-white hover:text-[#2B3288] transition-colors">
                                    <span class="truncate">{{ $tagLabel }}</span>
                                    <span class="text-[#2B3288] ml-1">0{{ $idx + 1 }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Livewire Interactive Catalog --}}
    <section id="katalog" class="py-12 md:py-20 scroll-mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between border-t border-[#111218]/40 pt-3 pb-8 text-xs font-bold uppercase tracking-[0.18em]">
                <span class="text-[#111218]">01</span>
                <span class="text-[#2B3288]">Katalog &amp; Filter Buku</span>
            </div>

            <livewire:catalog-filter />
        </div>
    </section>
</div>
@endsection
