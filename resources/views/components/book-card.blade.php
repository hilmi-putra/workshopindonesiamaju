@props(['book'])

@php
    $isOutOfStock = (int) $book->stock <= 0;
    $bookUrl = route('bookstore.show', $book->slug);
    $finalPrice = (float) $book->final_price;
    $hasDiscount = $finalPrice < (float) $book->price;
@endphp

<div class="group flex flex-col items-center bg-transparent w-full">
    {{-- Top Section: Cover + Organic Blob (Matches Next.js BookCard) --}}
    <a href="{{ $bookUrl }}" class="relative w-full aspect-[4/5] flex items-center justify-center mb-6">
        <div class="absolute inset-0 w-full h-full flex items-center justify-center pointer-events-none opacity-90 z-0 text-[#9B5C00] transition-transform duration-500 group-hover:scale-110 group-hover:-rotate-6">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="w-[105%] h-[105%] fill-current drop-shadow-sm">
                <path d="M47.5,-73.4C61.3,-66.2,71.7,-52.1,79.5,-36.8C87.3,-21.5,92.5,-4.9,90.2,10.6C87.8,26.1,77.9,40.4,66,51.8C54.1,63.1,40.2,71.5,25.3,77.1C10.4,82.8,-5.5,85.6,-20.9,82.8C-36.2,80.1,-50.9,71.8,-63.4,60C-75.9,48.1,-86.2,32.6,-89.4,15.6C-92.6,-1.5,-88.7,-20.1,-79,-35.1C-69.3,-50.1,-53.8,-61.5,-38.7,-68.2C-23.7,-74.9,-9.1,-76.9,6.5,-81C22,-85.1,44,-91.3,47.5,-73.4Z" transform="translate(100 100)" />
            </svg>
        </div>

        <div class="relative z-10 w-[62%] sm:w-[66%] aspect-[2/3] flex items-center justify-center transition-all duration-500 group-hover:-translate-y-3 group-hover:rotate-1">
            <img src="{{ $book->cover_url }}"
                 alt="{{ $book->title }}"
                 loading="lazy"
                 class="object-cover w-full h-full shadow-[0_12px_24px_-6px_rgba(0,0,0,0.22)] rounded-sm">

            @if($book->is_bestseller)
                <span class="absolute -top-3 -left-3 bg-[#171512] text-white rounded-full px-3 py-1 text-[10px] font-bold shadow-sm uppercase tracking-widest z-20">Top</span>
            @elseif($book->is_featured)
                <span class="absolute -top-3 -left-3 bg-[#DB8B00] text-white rounded-full px-3 py-1 text-[10px] font-bold shadow-sm uppercase tracking-widest z-20">Pilihan</span>
            @endif
        </div>
    </a>

    {{-- Bottom Info --}}
    <div class="w-full flex flex-col items-center text-center px-2 relative z-20 flex-1 justify-between">
        <div class="w-full">
            <p class="text-[#322855]/80 font-bold tracking-[0.18em] uppercase text-[10px] sm:text-[11px] mb-1.5 line-clamp-1">
                {{ $book->author?->name ?? 'Mega Press' }}
            </p>
            <a href="{{ $bookUrl }}" class="block">
                <h3 class="font-serif text-lg sm:text-[22px] font-normal leading-[1.15] text-[#322855] hover:text-[#DB8B00] transition-colors line-clamp-2 mb-2.5 min-h-[2.6rem]">
                    {{ $book->title }}
                </h3>
            </a>
        </div>

        <div class="flex flex-col items-center gap-2 w-full mt-1">
            <div class="flex items-baseline gap-2">
                <span class="font-semibold text-[#322855] text-base sm:text-lg">
                    Rp {{ number_format($finalPrice, 0, ',', '.') }}
                </span>
                @if($hasDiscount)
                    <span class="text-xs text-gray-400 line-through">
                        Rp {{ number_format((float) $book->price, 0, ',', '.') }}
                    </span>
                @endif
            </div>

            <div class="w-full mt-2 flex gap-1.5">
                @if($isOutOfStock)
                    <span class="w-full h-10 bg-gray-300 text-gray-600 text-xs font-bold uppercase tracking-widest flex items-center justify-center cursor-not-allowed">
                        Stok Habis
                    </span>
                @else
                    <a href="{{ $bookUrl }}"
                       class="flex-1 h-10 bg-[#171512] text-white hover:bg-[#DB8B00] text-xs font-bold uppercase tracking-widest flex items-center justify-center transition-colors">
                        Lihat &amp; Beli
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
