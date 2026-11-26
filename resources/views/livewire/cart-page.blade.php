<div>
    @if($errorMessage)
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm">
            {{ $errorMessage }}
        </div>
    @endif

    @if($items->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-gray-500 bg-white border border-[#DDD8C8] rounded-2xl">
            <p class="font-serif text-2xl text-[#171512] mb-2">Keranjang Anda kosong</p>
            <p class="text-sm mb-6">Tambahkan buku dari katalog Mega Bookstore untuk mulai memesan.</p>
            <a href="{{ route('bookstore.index') }}" class="px-8 py-3 rounded-full bg-[#DB8B00] hover:bg-[#b06f00] text-white text-xs font-bold uppercase tracking-widest">
                Jelajahi Bookstore
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
            {{-- Items List --}}
            <div class="lg:col-span-8 flex flex-col gap-5">
                @foreach($items as $item)
                    <div class="flex gap-5 bg-white p-5 sm:p-6 border border-[#DDD8C8] rounded-xl shadow-xs" wire:key="cart-item-{{ $item->book_id }}">
                        <a href="{{ route('bookstore.show', $item->slug) }}" class="w-24 sm:w-28 h-36 sm:h-40 shrink-0 bg-[#F0EBE1] rounded-md overflow-hidden">
                            <img src="{{ $item->cover_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                        </a>
                        <div class="flex-1 flex flex-col justify-between min-w-0">
                            <div>
                                <div class="flex justify-between items-start gap-4">
                                    <a href="{{ route('bookstore.show', $item->slug) }}" class="font-serif text-xl text-[#171512] hover:text-[#DB8B00] line-clamp-2">
                                        {{ $item->title }}
                                    </a>
                                    <button type="button" wire:click="remove({{ $item->book_id }})" class="text-gray-400 hover:text-red-600 text-sm font-semibold cursor-pointer">
                                        Hapus
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ $item->author }} &middot; Stok: {{ $item->stock }}</p>
                            </div>

                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mt-4 gap-4">
                                <div class="inline-flex items-center border border-gray-300 rounded-md p-1 bg-[#FAF8F4]">
                                    <button type="button" wire:click="decrement({{ $item->book_id }})" class="px-3 py-1.5 text-gray-600 hover:bg-gray-200 rounded-xs cursor-pointer">-</button>
                                    <span class="w-10 text-center text-sm font-bold">{{ $item->qty }}</span>
                                    <button type="button" wire:click="increment({{ $item->book_id }})" class="px-3 py-1.5 text-gray-600 hover:bg-gray-200 rounded-xs cursor-pointer">+</button>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">Rp {{ number_format($item->price, 0, ',', '.') }} / eks</p>
                                    <p class="font-bold text-xl text-[#171512]">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Summary Sidebar --}}
            <div class="lg:col-span-4 lg:sticky lg:top-28">
                <div class="bg-white p-7 border border-[#DDD8C8] rounded-2xl shadow-xs space-y-5">
                    <h2 class="text-xl font-bold pb-4 border-b border-gray-100">Ringkasan Belanja</h2>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Total Buku ({{ $count }} eks)</span>
                            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Ongkos Kirim</span>
                            <span class="text-xs italic">Dihitung di WhatsApp</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center text-xl font-black text-[#171512] pt-4 border-t border-gray-100">
                        <span>Total Buku</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>

                    <button type="button" wire:click="openCheckout"
                            class="w-full h-12 bg-[#25D366] hover:bg-[#1ebe5d] text-white font-bold uppercase tracking-widest text-xs transition-colors cursor-pointer">
                        Checkout via WhatsApp
                    </button>
                    <p class="text-[11px] text-gray-500 text-center">
                        Tidak perlu login! Isi data singkat lalu langsung terhubung ke Admin Mega Press.
                    </p>
                </div>
            </div>
        </div>

        {{-- Modal Form Data Pemesan WA --}}
        @if($showCheckoutModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div wire:click="$set('showCheckoutModal', false)" class="fixed inset-0 bg-black/50 backdrop-blur-xs"></div>
                <div class="relative z-10 w-full max-w-lg bg-white rounded-2xl border border-[#DDD8C8] shadow-2xl overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <h3 class="font-bold text-lg text-[#171512]">Checkout via WhatsApp</h3>
                        <button type="button" wire:click="$set('showCheckoutModal', false)" class="text-gray-400 hover:text-gray-700 text-xl cursor-pointer">&times;</button>
                    </div>
                    <form wire:submit="checkoutWhatsApp" class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-1">Nama Penerima *</label>
                            <input type="text" wire:model="customer_name" required class="w-full rounded-xl border border-[#DDD8C8] px-4 py-2.5 text-sm">
                            @error('customer_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-1">Nomor WhatsApp / HP *</label>
                            <input type="text" wire:model="customer_phone" placeholder="0812xxxxxxxx" required class="w-full rounded-xl border border-[#DDD8C8] px-4 py-2.5 text-sm">
                            @error('customer_phone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-1">Alamat Pengiriman Singkat *</label>
                            <textarea wire:model="customer_address" rows="3" placeholder="Jalan, Kecamatan, Kota, Kode Pos" required class="w-full rounded-xl border border-[#DDD8C8] px-4 py-2.5 text-sm"></textarea>
                            @error('customer_address') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-1">Catatan (Opsional)</label>
                            <input type="text" wire:model="notes" placeholder="Misal: bungkus rapi untuk kado" class="w-full rounded-xl border border-[#DDD8C8] px-4 py-2.5 text-sm">
                        </div>

                        <div class="bg-[#FAF8F4] rounded-xl p-4 text-xs text-gray-600 space-y-1">
                            <div class="flex justify-between font-bold text-[#171512]">
                                <span>Total Harga Buku ({{ $count }} eks)</span>
                                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <p>Ongkos kirim dan metode transfer akan dikonfirmasi admin di chat WhatsApp.</p>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" wire:click="$set('showCheckoutModal', false)" class="px-5 py-2.5 rounded-xl border border-[#DDD8C8] text-sm font-semibold">Batal</button>
                            <button type="submit" wire:loading.attr="disabled" wire:target="checkoutWhatsApp"
                                    class="px-6 py-2.5 rounded-xl bg-[#25D366] hover:bg-[#1ebe5d] text-white text-sm font-bold disabled:opacity-50 cursor-pointer">
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
