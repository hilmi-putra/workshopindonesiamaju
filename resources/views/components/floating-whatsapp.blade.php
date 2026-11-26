@php
    $waNumber = preg_replace('/[^0-9]/', '', (string) config('services.whatsapp.number', '6285724693474'));
    $waHref = 'https://wa.me/' . $waNumber . '?text=' . rawurlencode('Halo Admin Mega Press, saya ingin bertanya tentang buku / layanan penerbitan.');
@endphp

<a href="{{ $waHref }}" target="_blank" rel="noopener noreferrer"
   title="Chat Admin via WhatsApp"
   class="fixed bottom-6 right-6 z-40 inline-flex items-center gap-2.5 bg-[#25D366] hover:bg-[#1ebe5d] text-white font-bold text-xs uppercase tracking-wider px-4 py-3 rounded-full shadow-xl transition-transform hover:-translate-y-0.5">
    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12.031 2c-5.516 0-9.969 4.453-9.969 9.969 0 1.766.469 3.484 1.344 5l-1.406 5.156 5.281-1.391c1.469.797 3.109 1.219 4.75 1.219 5.516 0 9.969-4.453 9.969-9.969S17.547 2 12.031 2zm5.813 14.125c-.25.703-1.422 1.344-1.969 1.422-.516.078-1.156.109-1.875-.125-.438-.141-1-.328-1.719-.641-3-1.297-4.953-4.328-5.109-4.531-.141-.203-1.219-1.625-1.219-3.109 0-1.484.766-2.203 1.047-2.516.266-.297.594-.375.797-.375h.578c.188 0 .438-.078.688.516.25.609.859 2.094.938 2.25.078.156.125.344.031.547-.109.203-.156.344-.313.531-.156.172-.328.391-.469.531-.156.156-.328.328-.141.656.188.328.844 1.391 1.813 2.25 1.25 1.109 2.297 1.453 2.625 1.609.328.156.516.125.703-.094.188-.219.813-.953 1.031-1.281.219-.328.438-.266.734-.156.297.109 1.906.906 2.234 1.063.328.172.547.25.625.391.078.141.078.813-.172 1.516z"/></svg>
    <span class="hidden sm:inline">Chat WA</span>
</a>
