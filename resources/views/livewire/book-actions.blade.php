<div>
    @if($errorMessage)
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-xs font-medium">
            {{ $errorMessage }}
        </div>
    @endif

    @if($book->stock > 0)
        <div class="flex flex-wrap items-center gap-3 mb-5">
            <div class="flex items-center border border-[#C9CEDF] bg-[#F3F4F7]">
                <button type="button" wire:click="decrement" class="w-10 h-12 flex items-center justify-center text-[#111218] hover:bg-white cursor-pointer">-</button>
                <span class="w-12 h-12 flex items-center justify-center text-[#111218] font-bold">{{ $qty }}</span>
                <button type="button" wire:click="increment" class="w-10 h-12 flex items-center justify-center text-[#111218] hover:bg-white cursor-pointer">+</button>
            </div>

            <button type="button" wire:click="addToCart" wire:loading.attr="disabled"
                    class="h-12 px-7 bg-[#2B3288] hover:bg-[#1F256D] text-white font-bold tracking-[0.14em] text-xs uppercase transition-colors cursor-pointer">
                + Keranjang
            </button>

            <button type="button" wire:click="$set('showBuyModal', true)"
                    class="h-12 px-7 bg-[#25D366] hover:bg-[#1ebe5d] text-white font-bold tracking-[0.14em] text-xs uppercase transition-colors cursor-pointer">
                Beli via WhatsApp
            </button>
        </div>
    @else
        <div class="mb-5 p-4 bg-[#EAECEF] border border-[#C9CEDF] text-[#636778] text-xs font-bold uppercase tracking-wider">
            Stok sedang habis
        </div>
    @endif

    {{-- Modal Beli Langsung via WA --}}
    @if($showBuyModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div wire:click="$set('showBuyModal', false)" class="fixed inset-0 bg-[#111218]/55 backdrop-blur-xs"></div>
            <div class="relative z-10 w-full max-w-lg bg-white border border-[#C9CEDF] shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 bg-[#2B3288] text-white">
                    <h3 class="font-bold text-sm uppercase tracking-[0.15em]">Beli via WhatsApp</h3>
                    <button type="button" wire:click="$set('showBuyModal', false)" class="text-white/80 hover:text-white text-xl cursor-pointer">&times;</button>
                </div>

                <form wire:submit="buyViaWhatsApp" class="p-6 space-y-4">
                    <div class="bg-[#F3F4F7] border border-[#C9CEDF] p-3.5 text-xs space-y-1">
                        <p class="font-bold text-[#111218]">{{ $book->title }}</p>
                        <p class="text-[#636778]">{{ $qty }} eksemplar &times; Rp {{ number_format((float)$book->final_price, 0, ',', '.') }} = <strong class="text-[#2B3288]">Rp {{ number_format((float)$book->final_price * $qty, 0, ',', '.') }}</strong></p>
                    </div>

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
                        <input type="text" wire:model="notes" class="w-full border border-[#C9CEDF] px-4 py-2.5 text-sm focus:outline-none focus:border-[#2B3288]">
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="$set('showBuyModal', false)" class="px-5 py-2.5 border border-[#C9CEDF] text-xs font-bold uppercase tracking-wider text-[#636778] cursor-pointer">Batal</button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="buyViaWhatsApp"
                                class="px-6 py-2.5 bg-[#25D366] hover:bg-[#1ebe5d] text-white text-xs font-bold uppercase tracking-wider disabled:opacity-50 cursor-pointer">
                            <span wire:loading.remove wire:target="buyViaWhatsApp">Lanjut ke WhatsApp</span>
                            <span wire:loading wire:target="buyViaWhatsApp">Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
