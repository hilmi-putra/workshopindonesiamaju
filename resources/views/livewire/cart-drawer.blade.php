<div>
    <div x-data="{ show: @entangle('open') }"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 flex justify-end">

        <div @click="show = false" class="fixed inset-0 bg-black/45 backdrop-blur-xs"></div>

        <div class="relative z-10 w-full max-w-md bg-white h-full flex flex-col shadow-2xl">
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                <div>
                    <h2 class="font-serif text-2xl font-bold text-[#171512]">Keranjang Belanja</h2>
                    <p class="text-xs text-gray-500">{{ $count }} item dipilih</p>
                </div>
                <button type="button" @click="show = false" class="size-9 rounded-full hover:bg-gray-100 flex items-center justify-center text-gray-500 text-xl cursor-pointer">&times;</button>
            </div>

            @if(session('cart_error'))
                <div class="mx-6 mt-4 p-3 rounded-lg bg-red-50 text-red-700 text-xs">{{ session('cart_error') }}</div>
            @endif

            <div class="flex-1 overflow-y-auto px-6 py-4 divide-y divide-[#E8E3D9]">
                @forelse($items as $item)
                    <div class="flex gap-4 py-4" wire:key="drawer-item-{{ $item->book_id }}">
                        <a href="{{ route('bookstore.show', $item->slug) }}" class="w-16 h-24 bg-[#F0EBE1] rounded-md shrink-0 overflow-hidden flex items-center justify-center p-1">
                            <img src="{{ $item->cover_url }}" alt="{{ $item->title }}" class="h-full w-full object-cover rounded-xs">
                        </a>
                        <div class="flex-1 min-w-0 flex flex-col justify-between">
                            <div class="flex justify-between gap-2">
                                <a href="{{ route('bookstore.show', $item->slug) }}" class="font-serif text-base text-[#171512] hover:text-[#DB8B00] line-clamp-2 leading-snug">
                                    {{ $item->title }}
                                </a>
                                <button type="button" wire:click="remove({{ $item->book_id }})" class="text-gray-400 hover:text-red-600 text-sm shrink-0 cursor-pointer">&times;</button>
                            </div>

                            <div class="flex items-center justify-between mt-3">
                                <div class="inline-flex items-center border border-gray-200 rounded-md">
                                    <button type="button" wire:click="decrement({{ $item->book_id }})" class="px-2.5 py-1 text-xs hover:bg-gray-100 cursor-pointer">-</button>
                                    <span class="px-2.5 text-xs font-bold">{{ $item->qty }}</span>
                                    <button type="button" wire:click="increment({{ $item->book_id }})" class="px-2.5 py-1 text-xs hover:bg-gray-100 cursor-pointer">+</button>
                                </div>
                                <span class="text-sm font-bold text-[#171512]">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-20 text-center text-gray-500">
                        <p class="font-serif text-xl text-[#171512] mb-2">Keranjang masih kosong</p>
                        <p class="text-xs mb-6">Temukan buku favorit Anda di Mega Bookstore.</p>
                        <a href="{{ route('bookstore.index') }}" @click="show = false"
                           class="inline-block px-6 py-2.5 bg-[#DB8B00] text-white rounded-full text-xs font-bold uppercase tracking-wider">
                            Jelajahi Katalog
                        </a>
                    </div>
                @endforelse
            </div>

            @if($items->isNotEmpty())
                <div class="p-6 border-t border-gray-100 bg-[#FAF8F4] space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 font-medium">Subtotal</span>
                        <span class="text-xl font-black text-[#171512]">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <p class="text-[11px] text-gray-500">Ongkos kirim &amp; metode pembayaran akan dikonfirmasi bersama admin via WhatsApp.</p>
                    <a href="{{ route('cart.index') }}"
                       class="w-full h-12 bg-[#084c3c] hover:bg-[#063a2e] text-white font-bold uppercase tracking-widest text-xs flex items-center justify-center transition-colors">
                        Lanjut ke Checkout WhatsApp
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
