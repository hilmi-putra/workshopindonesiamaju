<div>
    @if($errorMessage)
        <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-xs">
            {{ $errorMessage }}
        </div>
    @endif

    @if($book->stock > 0)
        <div class="flex flex-wrap items-center gap-3 mb-5">
            <div class="flex items-center border border-gray-200 bg-white">
                <button type="button" wire:click="decrement" class="w-10 h-12 flex items-center justify-center text-gray-600 hover:bg-gray-50 cursor-pointer">-</button>
                <span class="w-12 h-12 flex items-center justify-center text-[#171512] font-semibold">{{ $qty }}</span>
                <button type="button" wire:click="increment" class="w-10 h-12 flex items-center justify-center text-gray-600 hover:bg-gray-50 cursor-pointer">+</button>
            </div>

            <button type="button" wire:click="addToCart" wire:loading.attr="disabled"
                    class="h-12 px-7 bg-[#084c3c] hover:bg-[#063a2e] text-white font-semibold tracking-wider text-xs uppercase transition-colors cursor-pointer">
                + Keranjang
            </button>

            <button type="button" wire:click="$set('showBuyModal', true)"
                    class="h-12 px-7 bg-[#25D366] hover:bg-[#1ebe5d] text-white font-bold tracking-wider text-xs uppercase transition-colors cursor-pointer">
                Beli via WhatsApp
            </button>
        </div>
    @else
        <div class="mb-5 p-4 bg-gray-100 text-gray-600 text-xs font-bold uppercase tracking-wider">
            Stok sedang habis
        </div>
    @endif

    {{-- Modal Beli Langsung via WA --}}
    @if($showBuyModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div wire:click="$set('showBuyModal', false)" class="fixed inset-0 bg-black/50 backdrop-blur-xs"></div>
            <div class="relative z-10 w-full max-w-lg bg-white rounded-2xl border border-[#DDD8C8] shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-lg text-[#171512]">Beli via WhatsApp</h3>
                    <button type="button" wire:click="$set('showBuyModal', false)" class="text-gray-400 hover:text-gray-700 text-xl cursor-pointer">&times;</button>
                </div>

                <form wire:submit="buyViaWhatsApp" class="p-6 space-y-4">
                    <div class="bg-[#FAF8F4] rounded-xl p-3.5 text-xs space-y-1">
                        <p class="font-bold text-[#171512]">{{ $book->title }}</p>
                        <p class="text-gray-600">{{ $qty }} eksemplar &times; Rp {{ number_format((float)$book->final_price, 0, ',', '.') }} = <strong class="text-[#171512]">Rp {{ number_format((float)$book->final_price * $qty, 0, ',', '.') }}</strong></p>
                    </div>

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
                        <input type="text" wire:model="notes" class="w-full rounded-xl border border-[#DDD8C8] px-4 py-2.5 text-sm">
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="$set('showBuyModal', false)" class="px-5 py-2.5 rounded-xl border border-[#DDD8C8] text-sm font-semibold">Batal</button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="buyViaWhatsApp"
                                class="px-6 py-2.5 rounded-xl bg-[#25D366] hover:bg-[#1ebe5d] text-white text-sm font-bold disabled:opacity-50 cursor-pointer">
                            <span wire:loading.remove wire:target="buyViaWhatsApp">Lanjut ke WhatsApp</span>
                            <span wire:loading wire:target="buyViaWhatsApp">Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
