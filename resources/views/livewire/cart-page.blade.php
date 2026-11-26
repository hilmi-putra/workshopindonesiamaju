<div>
    @if($errorMessage)
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 text-sm">
            {{ $errorMessage }}
        </div>
    @endif

    @if($items->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-[#636778] bg-white border border-[#C9CEDF]">
            <p class="font-serif text-3xl text-[#111218] mb-2">Keranjang Anda kosong</p>
            <p class="text-sm mb-6">Tambahkan buku dari katalog WIM Bookstore untuk mulai memesan.</p>
            <a href="{{ route('bookstore.index') }}" class="px-8 py-3.5 bg-[#2B3288] hover:bg-[#1F256D] text-white text-xs font-bold uppercase tracking-[0.15em] transition-colors">
                Jelajahi Bookstore
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            {{-- Items List --}}
            <div class="lg:col-span-8 flex flex-col border-t border-l border-[#C9CEDF] bg-white">
                @foreach($items as $item)
                    <div class="flex gap-5 p-5 sm:p-6 border-r border-b border-[#C9CEDF]" wire:key="cart-item-{{ $item->book_id }}">
                        <a href="{{ route('bookstore.show', $item->slug) }}" class="w-24 sm:w-28 h-36 sm:h-40 shrink-0 bg-[#EAECEF] border border-[#C9CEDF] overflow-hidden">
                            <img src="{{ $item->cover_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                        </a>
                        <div class="flex-1 flex flex-col justify-between min-w-0">
                            <div>
                                <div class="flex justify-between items-start gap-4">
                                    <a href="{{ route('bookstore.show', $item->slug) }}" class="font-serif text-xl sm:text-2xl text-[#111218] hover:text-[#2B3288] line-clamp-2">
                                        {{ $item->title }}
                                    </a>
                                    <button type="button" wire:click="remove({{ $item->book_id }})" class="text-[#636778] hover:text-red-600 text-xs font-bold uppercase tracking-wider cursor-pointer">
                                        Hapus
                                    </button>
                                </div>
                                <p class="text-xs text-[#636778] mt-1">{{ $item->author }} &middot; Stok: {{ $item->stock }}</p>
                            </div>

                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mt-4 gap-4">
                                <div class="inline-flex items-center border border-[#C9CEDF] bg-[#F3F4F7]">
                                    <button type="button" wire:click="decrement({{ $item->book_id }})" class="px-3.5 py-2 text-sm text-[#111218] hover:bg-white cursor-pointer">-</button>
                                    <span class="w-10 text-center text-sm font-bold text-[#111218]">{{ $item->qty }}</span>
                                    <button type="button" wire:click="increment({{ $item->book_id }})" class="px-3.5 py-2 text-sm text-[#111218] hover:bg-white cursor-pointer">+</button>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-[#636778]">Rp {{ number_format($item->price, 0, ',', '.') }} / eks</p>
                                    <p class="font-extrabold text-xl text-[#2B3288]">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Summary Sidebar --}}
            <div class="lg:col-span-4 lg:sticky lg:top-28">
                <div class="bg-white p-7 border border-[#C9CEDF] space-y-5">
                    <h2 class="text-sm font-bold uppercase tracking-[0.16em] text-[#2B3288] pb-4 border-b border-[#111218]/25">Ringkasan Belanja</h2>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-[#636778]">
                            <span>Total Buku ({{ $count }} eks)</span>
                            <span class="font-semibold text-[#111218]">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-[#636778]">
                            <span>Ongkos Kirim</span>
                            <span class="text-xs italic">Dihitung di WhatsApp</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center text-xl font-extrabold text-[#2B3288] pt-4 border-t border-[#C9CEDF]">
                        <span class="text-[#111218]">Total Buku</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>

                    <button type="button" wire:click="openCheckout"
                            class="w-full h-12 bg-[#25D366] hover:bg-[#1ebe5d] text-white font-bold uppercase tracking-[0.15em] text-xs transition-colors cursor-pointer">
                        Checkout via WhatsApp
                    </button>
                    <p class="text-[11px] text-[#636778] text-center">
                        Tidak perlu login! Isi data singkat lalu langsung terhubung ke Admin Workshop Indonesia Maju.
                    </p>
                </div>
            </div>
        </div>

        {{-- Modal Form Data Pemesan WA --}}
        @if($showCheckoutModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div wire:click="$set('showCheckoutModal', false)" class="fixed inset-0 bg-[#111218]/55 backdrop-blur-xs"></div>
                <div class="relative z-10 w-full max-w-lg bg-white border border-[#C9CEDF] shadow-2xl overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 bg-[#2B3288] text-white">
                        <h3 class="font-bold text-sm uppercase tracking-[0.15em]">Checkout via WhatsApp</h3>
                        <button type="button" wire:click="$set('showCheckoutModal', false)" class="text-white/80 hover:text-white text-xl cursor-pointer">&times;</button>
                    </div>
                    <form wire:submit="checkoutWhatsApp" class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-[#111218] mb-1">Nama Penerima *</label>
                            <input type="text" wire:model="customer_name" required class="w-full border border-[#C9CEDF] px-4 py-2.5 text-sm focus:outline-none focus:border-[#2B3288]">
                            @error('customer_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-[#111218] mb-1">Nomor WhatsApp / HP *</label>
                            <input type="text" wire:model="customer_phone" placeholder="0812xxxxxxxx" required class="w-full border border-[#C9CEDF] px-4 py-2.5 text-sm focus:outline-none focus:border-[#2B3288]">
                            @error('customer_phone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-[#111218] mb-1">Alamat Pengiriman Singkat *</label>
                            <textarea wire:model="customer_address" rows="3" placeholder="Jalan, Kecamatan, Kota, Kode Pos" required class="w-full border border-[#C9CEDF] px-4 py-2.5 text-sm focus:outline-none focus:border-[#2B3288]"></textarea>
                            @error('customer_address') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-[#111218] mb-1">Catatan (Opsional)</label>
                            <input type="text" wire:model="notes" placeholder="Misal: bungkus rapi untuk kado" class="w-full border border-[#C9CEDF] px-4 py-2.5 text-sm focus:outline-none focus:border-[#2B3288]">
                        </div>

                        <div class="bg-[#F3F4F7] border border-[#C9CEDF] p-4 text-xs text-[#636778] space-y-1">
                            <div class="flex justify-between font-bold text-[#111218]">
                                <span>Total Harga Buku ({{ $count }} eks)</span>
                                <span class="text-[#2B3288]">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <p>Ongkos kirim dan metode transfer akan dikonfirmasi admin di chat WhatsApp.</p>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" wire:click="$set('showCheckoutModal', false)" class="px-5 py-2.5 border border-[#C9CEDF] text-xs font-bold uppercase tracking-wider text-[#636778] cursor-pointer">Batal</button>
                            <button type="submit" wire:loading.attr="disabled" wire:target="checkoutWhatsApp"
                                    class="px-6 py-2.5 bg-[#25D366] hover:bg-[#1ebe5d] text-white text-xs font-bold uppercase tracking-wider disabled:opacity-50 cursor-pointer">
                                <span wire:loading.remove wire:target="checkoutWhatsApp">Kirim Pesanan ke WhatsApp</span>
                                <span wire:loading wire:target="checkoutWhatsApp">Memproses...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endif
</div>
