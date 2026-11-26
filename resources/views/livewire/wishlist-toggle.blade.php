<button type="button"
        wire:click="toggle"
        wire:loading.attr="disabled"
        class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[0.14em] transition-colors cursor-pointer {{ $isWishlisted ? 'text-[#2B3288]' : 'text-[#636778] hover:text-[#2B3288]' }}">
    <svg class="w-4 h-4 {{ $isWishlisted ? 'fill-[#2B3288] text-[#2B3288]' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
    </svg>
    <span>{{ $isWishlisted ? 'Tersimpan di Wishlist' : 'Simpan ke Wishlist' }}</span>
</button>
