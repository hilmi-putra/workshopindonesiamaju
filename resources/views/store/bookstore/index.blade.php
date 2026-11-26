@extends('layouts.app')

@section('title', 'Mega Bookstore | Katalog Buku Resmi Mega Press')
@section('meta_description', 'Jelajahi koleksi buku fiksi, non-fiksi, buku ajar, dan referensi ilmiah resmi terbitan Mega Press Nusantara.')

@section('content')
<div class="flex flex-col bg-[#FAF8F4] min-h-screen">
    {{-- Store Hero Section (Matches Next.js StoreHeroSection BOOK ROOM + Domino Spines) --}}
    <section class="pt-28 md:pt-36 overflow-hidden border-b-2 border-[#F9990E]"
             style="background-color: #FCF9F5; background-image: linear-gradient(to right, rgba(249, 153, 14, 0.35) 1px, transparent 1px), linear-gradient(to bottom, rgba(249, 153, 14, 0.35) 1px, transparent 1px); background-size: 180px 180px;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-end justify-between w-full gap-12 relative">
                {{-- Left Column --}}
                <div class="flex flex-col items-start max-w-lg pb-14 relative z-10">
                    <div class="flex items-center text-[64px] md:text-[96px] font-sans font-bold leading-none text-[#171512] tracking-tighter mb-8">
                        <div class="flex flex-col leading-[0.85] mr-3 md:mr-6">
                            <span>B</span>
                            <span>R</span>
                        </div>
                        <div class="flex items-center -mx-2 z-10">
                            <svg width="52" height="125" viewBox="0 0 60 150" class="md:w-[75px] md:h-[180px] rotate-[15deg] z-10" fill="none">
                                <rect x="5" y="5" width="50" height="140" rx="25" stroke="#F9990E" stroke-width="10" />
                            </svg>
                            <svg width="52" height="125" viewBox="0 0 60 150" class="md:w-[75px] md:h-[180px] -ml-5 md:-ml-7 rotate-[-5deg] z-0" fill="none">
                                <rect x="5" y="5" width="50" height="140" rx="25" stroke="#E0790A" stroke-width="10" />
                            </svg>
                        </div>
                        <div class="flex flex-col leading-[0.85] ml-3 md:ml-6">
                            <span>K</span>
                            <span>M</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 mb-10">
                        <a href="#katalog" class="flex items-center justify-between border-2 border-[#171512] rounded-full px-4 py-2 w-52 bg-white hover:bg-gray-50 transition-colors">
                            <span class="font-medium text-[#171512] text-base">Mulai</span>
                            <span class="bg-[#F9990E] text-white px-2.5 py-1 rounded-full text-xs font-bold">&darr;</span>
                        </a>
                        <span class="font-bold text-[#171512] text-base">Jelajahi koleksi</span>
                    </div>

                    <p class="text-lg md:text-xl font-medium text-[#171512] leading-snug">
                        Temukan <span class="underline decoration-[#F9990E] decoration-2 underline-offset-4">inspirasi bacaan</span> terbaik terbitan Mega Press untuk menemani keseharian Anda.
                    </p>
                </div>

                {{-- Right Column: Tilted Domino Book Spines --}}
                <div class="hidden sm:block w-full md:w-1/2 h-[380px] md:h-[440px] relative z-10">
                    @php
                        $spines = [
                            ['label' => 'Terlaris', 'color' => 'bg-[#F9990E]', 'rotate' => '26deg', 'left' => 'left-[6%]', 'z' => 'z-10'],
                            ['label' => 'Fiksi', 'color' => 'bg-[#E0790A]', 'rotate' => '20deg', 'left' => 'left-[30%]', 'z' => 'z-20'],
                            ['label' => 'Non-Fiksi', 'color' => 'bg-[#9A5200]', 'rotate' => '14deg', 'left' => 'left-[54%]', 'z' => 'z-30'],
                            ['label' => 'Pilihan', 'color' => 'bg-[#FCD291]', 'rotate' => '8deg', 'left' => 'left-[78%]', 'z' => 'z-40'],
                        ];
                    @endphp
                    @foreach($spines as $sp)
                        <div class="absolute bottom-0 {{ $sp['left'] }} {{ $sp['z'] }} transition-transform duration-300 hover:-translate-y-6">
                            <a href="#katalog"
                               style="transform: rotate({{ $sp['rotate'] }}); transform-origin: bottom left;"
                               class="block w-16 md:w-20 h-[310px] md:h-[360px] {{ $sp['color'] }} flex flex-col items-center justify-between py-6 shadow-[-4px_4px_0px_rgba(23,21,18,1)] border-2 border-[#171512]">
                                <div class="w-8 h-8 rounded-full bg-[#171512] flex items-center justify-center text-white text-xs font-bold">&rarr;</div>
                                <span class="text-[#171512] font-semibold text-xl whitespace-nowrap tracking-wider mb-6" style="writing-mode: vertical-rl; transform: rotate(180deg);">
                                    {{ $sp['label'] }}
                                </span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Livewire Interactive Catalog --}}
    <section id="katalog" class="py-12 md:py-20 scroll-mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:catalog-filter />
        </div>
    </section>
</div>
@endsection
