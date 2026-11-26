<footer class="bg-[#F3F4F7] text-[#111218] border-t border-[#111218]/20">
    {{-- Top Hairline Meta Band --}}
    <div class="border-b border-[#C9CEDF] bg-[#ECEEF5]/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-wrap items-center justify-between gap-3 text-[10px] font-bold uppercase tracking-[0.2em] text-[#636778]">
            <span>COLOPHON // WORKSHOP INDONESIA MAJU</span>
            <span>PUBLISHING HOUSE &amp; BOOKSTORE</span>
            <span>INDONESIA</span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            {{-- Left Column: Tilted Editorial Spines + About --}}
            <div class="lg:col-span-6 flex flex-col justify-between space-y-8">
                {{-- Stacked Lavender-Indigo Book Spines Motif --}}
                <div class="flex items-end gap-2 h-28 select-none">
                    <div class="w-10 h-24 bg-[#DDE2F3] border border-[#2B3288]/25 flex items-center justify-center -rotate-6">
                        <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-[#2B3288]" style="writing-mode: vertical-rl; transform: rotate(180deg);">PENERBIT</span>
                    </div>
                    <div class="w-11 h-28 bg-[#E7EBF7] border border-[#2B3288]/25 flex items-center justify-center rotate-2">
                        <span class="text-[9px] font-serif font-bold tracking-[0.15em] text-[#111218]" style="writing-mode: vertical-rl; transform: rotate(180deg);">NUSANTARA</span>
                    </div>
                    <div class="w-12 h-24 bg-[#2B3288] text-white flex items-center justify-center -rotate-2">
                        <span class="text-[9px] font-bold uppercase tracking-[0.2em]" style="writing-mode: vertical-rl; transform: rotate(180deg);">TERPERCAYA</span>
                    </div>
                    <div class="w-9 h-20 bg-white border border-[#2B3288]/30 flex items-center justify-center rotate-6 text-[#2B3288] font-serif italic font-bold text-sm">
                        WIM
                    </div>
                </div>

                <div class="space-y-4 max-w-lg">
                    <p class="text-sm leading-relaxed text-[#636778]">
                        Penerbit anggota IKAPI yang dipercaya penulis di seluruh nusantara. Menerbitkan berbagai genre mulai dari Fiksi, Novel, hingga Buku Ajar dan Jurnal Ilmiah.
                    </p>
                    <div class="border-t border-[#C9CEDF] pt-3 flex flex-wrap gap-x-6 gap-y-2 text-xs">
                        <span class="uppercase tracking-[0.15em] font-bold text-[#636778]">WhatsApp Admin &amp; Redaksi</span>
                        <span class="font-bold text-[#2B3288]">{{ config('services.whatsapp.number', '6285724693474') }}</span>
                    </div>
                </div>

                <div class="pt-2 flex items-center gap-3">
                    <div class="size-10 bg-white border border-[#C9CEDF] p-1.5 flex items-center justify-center shrink-0">
                        <img src="{{ asset('wim.png') }}" alt="Logo Workshop Indonesia Maju" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <span class="font-serif font-bold text-xl tracking-tight text-[#111218] block leading-none">Workshop Indonesia Maju</span>
                        <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#2B3288] mt-1 block">Editorial &amp; Bookstore</span>
                    </div>
                </div>
            </div>

            {{-- Right Column: Architectural Ledger Links & CTA --}}
            <div class="lg:col-span-6 flex flex-col justify-between h-full lg:pl-8 lg:border-l border-[#C9CEDF] space-y-10">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#2B3288] mb-3">Mulai Menerbitkan</p>
                    <h2 class="font-serif text-3xl sm:text-[42px] leading-[1.02] tracking-[-0.03em] text-[#111218] mb-6">
                        Siap menerbitkan <em class="italic font-normal text-[#2B3288]">karya terbaik</em> Anda bersama kami?
                    </h2>

                    <a href="{{ route('home') }}#ajukan-naskah"
                       class="inline-flex items-center gap-3 bg-[#2B3288] hover:bg-[#1F256D] text-white text-xs font-bold uppercase tracking-[0.15em] px-7 py-3.5 transition-colors">
                        <span>Ajukan Naskah</span>
                        <span>&rarr;</span>
                    </a>
                </div>

                <div class="border-t border-[#111218]/20 divide-y divide-[#C9CEDF] text-xs font-bold uppercase tracking-[0.14em]">
                    <a href="{{ route('home') }}" class="py-3 flex items-center justify-between hover:text-[#2B3288] transition-colors">
                        <span>Home</span>
                        <span class="text-[#C9CEDF]">01</span>
                    </a>
                    <a href="{{ route('bookstore.index') }}" class="py-3 flex items-center justify-between hover:text-[#2B3288] transition-colors">
                        <span>WIM Bookstore</span>
                        <span class="text-[#C9CEDF]">02</span>
                    </a>
                    <a href="{{ route('blog.index') }}" class="py-3 flex items-center justify-between hover:text-[#2B3288] transition-colors">
                        <span>Blog &amp; Editorial</span>
                        <span class="text-[#C9CEDF]">03</span>
                    </a>
                    <a href="{{ route('home') }}#layanan" class="py-3 flex items-center justify-between hover:text-[#2B3288] transition-colors">
                        <span>Layanan Penerbitan</span>
                        <span class="text-[#C9CEDF]">04</span>
                    </a>
                </div>

                <div class="text-[11px] text-[#636778] flex flex-wrap items-center justify-between gap-2 pt-2">
                    <p>&copy; {{ date('Y') }} WORKSHOP INDONESIA MAJU. All rights reserved.</p>
                    <p class="uppercase tracking-[0.12em]">Dibuat &amp; diterbitkan oleh Workshop Indonesia Maju</p>
                </div>
            </div>
        </div>
    </div>
</footer>
