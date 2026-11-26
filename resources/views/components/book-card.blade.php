@props(['book'])

@php
    $isOutOfStock = (int) $book->stock <= 0;
    $bookUrl = route('bookstore.show', $book->slug);
    $finalPrice = (float) $book->final_price;
    $hasDiscount = $finalPrice < (float) $book->price;
@endphp

<div class="group flex flex-col bg-white border border-[#C9CEDF] hover:border-[#2B3288] transition-colors w-full">
    {{-- Top Section: Architectural Spine Frame + Cover --}}
    <a href="{{ $bookUrl }}" class="relative w-full aspect-[4/5] bg-[#EAECEF] border-b border-[#C9CEDF] flex items-center justify-center p-6 overflow-hidden">
        {{-- Subtle tilted architectural indigo book-spine shadow behind cover --}}
        <div class="absolute inset-x-[20%] top-[12%] bottom-[10%] bg-[#DDE2F3] border border-[#2B3288]/20 rotate-3 transition-transform duration-500 group-hover:rotate-6 group-hover:translate-x-1"></div>

        <div class="relative z-10 w-[64%] sm:w-[66%] aspect-[2/3] flex items-center justify-center bg-white border border-[#111218]/15 shadow-sm transition-transform duration-500 group-hover:-translate-y-2">
            <img src="{{ $book->cover_url }}"
                 alt="{{ $book->title }}"
                 loading="lazy"
                 class="object-cover w-full h-full">

            @if($book->is_bestseller)
                <span class="absolute -top-2.5 -left-2.5 bg-[#111218] text-white px-2.5 py-1 text-[9px] font-bold uppercase tracking-[0.18em] z-20">Top</span>
            @elseif($book->is_featured)
                <span class="absolute -top-2.5 -left-2.5 bg-[#2B3288] text-white px-2.5 py-1 text-[9px] font-bold uppercase tracking-[0.18em] z-20">Pilihan</span>
            @endif
        </div>
    </a>

    {{-- Bottom Info --}}
    <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between text-left">
        <div>
            <p class="text-[#2B3288] font-bold tracking-[0.16em] uppercase text-[10px] mb-1.5 line-clamp-1">
                {{ $book->author?->name ?? 'Workshop Indonesia Maju' }}
            </p>
            <a href="{{ $bookUrl }}" class="block">
                <h3 class="font-serif text-lg sm:text-[20px] font-normal leading-[1.15] tracking-[-0.01em] text-[#111218] group-hover:text-[#2B3288] transition-colors line-clamp-2 mb-3 min-h-[2.4rem]">
                    {{ $book->title }}
                </h3>
            </a>
        </div>

        <div class="pt-3 border-t border-[#E3E7F3] space-y-3">
            <div class="flex items-baseline justify-between gap-2">
                <span class="font-bold text-[#111218] text-sm sm:text-base">
                    Rp {{ number_format($finalPrice, 0, ',', '.') }}
                </span>
                @if($hasDiscount)
                    <span class="text-[11px] text-[#636778] line-through">
                        Rp {{ number_format((float) $book->price, 0, ',', '.') }}
                    </span>
                @endif
            </div>

            <div>
                @if($isOutOfStock)
                    <span class="w-full h-10 bg-[#EAECEF] border border-[#C9CEDF] text-[#636778] text-[11px] font-bold uppercase tracking-[0.16em] flex items-center justify-center cursor-not-allowed">
                        Stok Habis
                    </span>
                @else
                    <a href="{{ $bookUrl }}"
                       class="w-full h-10 bg-[#111218] text-white hover:bg-[#2B3288] text-[11px] font-bold uppercase tracking-[0.16em] flex items-center justify-between px-4 transition-colors">
                        <span>Lihat &amp; Beli</span>
                        <span>&rarr;</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
