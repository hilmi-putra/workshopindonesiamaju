<div>
    <div x-data="{ show: @entangle('open') }"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 flex justify-end">

        <div @click="show = false" class="fixed inset-0 bg-[#111218]/50 backdrop-blur-xs"></div>

        <div class="relative z-10 w-full max-w-md bg-white h-full flex flex-col border-l border-[#C9CEDF] shadow-2xl">
            <div class="flex items-center justify-between px-6 py-5 border-b border-[#C9CEDF] bg-[#F3F4F7]">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#2B3288]">WIM Bookstore</p>
                    <h2 class="font-serif text-2xl font-normal text-[#111218]">Keranjang Belanja</h2>
                    <p class="text-xs text-[#636778]">{{ $count }} item dipilih</p>
                </div>
                <button type="button" @click="show = false" class="size-9 border border-[#C9CEDF] bg-white hover:border-[#2B3288] flex items-center justify-center text-[#111218] text-xl cursor-pointer">&times;</button>
            </div>

            @if(session('cart_error'))
                <div class="mx-6 mt-4 p-3 bg-red-50 border border-red-200 text-red-700 text-xs">{{ session('cart_error') }}</div>
            @endif

            <div class="flex-1 overflow-y-auto px-6 py-4 divide-y divide-[#E3E7F3]">
                @forelse($items as $item)
                    <div class="flex gap-4 py-4" wire:key="drawer-item-{{ $item->book_id }}">
                        <a href="{{ route('bookstore.show', $item->slug) }}" class="w-16 h-24 bg-[#EAECEF] border border-[#C9CEDF] shrink-0 overflow-hidden flex items-center justify-center p-1">
                            <img src="{{ $item->cover_url }}" alt="{{ $item->title }}" class="h-full w-full object-cover">
                        </a>
                        <div class="flex-1 min-w-0 flex flex-col justify-between">
                            <div class="flex justify-between gap-2">
                                <a href="{{ route('bookstore.show', $item->slug) }}" class="font-serif text-base text-[#111218] hover:text-[#2B3288] line-clamp-2 leading-snug">
                                    {{ $item->title }}
                                </a>
                                <button type="button" wire:click="remove({{ $item->book_id }})" class="text-[#636778] hover:text-red-600 text-sm shrink-0 cursor-pointer">&times;</button>
                            </div>

                            <div class="flex items-center justify-between mt-3">
                                <div class="inline-flex items-center border border-[#C9CEDF] bg-[#F3F4F7]">
                                    <button type="button" wire:click="decrement({{ $item->book_id }})" class="px-2.5 py-1 text-xs hover:bg-white cursor-pointer">-</button>
                                    <span class="px-2.5 text-xs font-bold text-[#111218]">{{ $item->qty }}</span>
                                    <button type="button" wire:click="increment({{ $item->book_id }})" class="px-2.5 py-1 text-xs hover:bg-white cursor-pointer">+</button>
                                </div>
                                <span class="text-sm font-bold text-[#2B3288]">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-20 text-center text-[#636778]">
                        <p class="font-serif text-2xl text-[#111218] mb-2">Keranjang masih kosong</p>
                        <p class="text-xs mb-6">Temukan buku favorit Anda di WIM Bookstore.</p>
                        <a href="{{ route('bookstore.index') }}" @click="show = false"
                           class="inline-block px-6 py-3 bg-[#2B3288] hover:bg-[#1F256D] text-white text-xs font-bold uppercase tracking-[0.15em] transition-colors">
                            Jelajahi Katalog
                        </a>
                    </div>
                @endforelse
            </div>

            @if($items->isNotEmpty())
                <div class="p-6 border-t border-[#C9CEDF] bg-[#F3F4F7] space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-xs uppercase tracking-[0.14em] text-[#636778] font-bold">Subtotal</span>
                        <span class="text-xl font-extrabold text-[#2B3288]">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <p class="text-[11px] text-[#636778]">Ongkos kirim &amp; metode pembayaran akan dikonfirmasi bersama admin via WhatsApp.</p>
                    <a href="{{ route('cart.index') }}"
                       class="w-full h-12 bg-[#2B3288] hover:bg-[#1F256D] text-white font-bold uppercase tracking-[0.15em] text-xs flex items-center justify-center transition-colors">
                        Lanjut ke Checkout WhatsApp &rarr;
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
