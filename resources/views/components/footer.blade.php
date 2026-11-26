<footer class="bg-[#FAF8F4] text-[#171512] py-16 md:py-20 border-t border-[#DDD8C8]/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start gap-12 md:gap-8">
            {{-- Left Column --}}
            <div class="md:w-1/2 flex flex-col justify-between h-full min-h-[250px]">
                <div>
                    {{-- Floating Pill Rectangles (Matches Next.js Footer) --}}
                    <div class="relative h-40 w-full max-w-[340px] mb-6 select-none">
                        <div class="absolute top-4 left-4 border-2 border-[#171512] text-[#171512] rounded-full px-4 py-1.5 text-lg font-bold -rotate-12 hover:rotate-0 transition-transform bg-transparent">
                            Penerbit
                        </div>
                        <div class="absolute top-14 left-20 border-2 border-[#171512] text-[#171512] rounded-full px-6 py-2 text-2xl font-serif font-bold rotate-6 hover:rotate-0 transition-transform bg-transparent">
                            Nusantara
                        </div>
                        <div class="absolute top-28 left-8 bg-[#6E0000] text-white rounded-full px-6 py-2 text-2xl font-bold -rotate-3 hover:rotate-0 transition-transform shadow-[-4px_4px_0px_rgba(219,139,0,1)] border-2 border-[#171512]">
                            Terpercaya
                        </div>
                        <div class="absolute top-24 left-60 border-2 border-[#171512] rounded-full w-10 h-10 flex items-center justify-center rotate-12 bg-[#DB8B00]">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                        </div>
                    </div>

                    <p class="text-[14px] md:text-[15px] leading-relaxed font-medium max-w-[85%] mb-8 opacity-90">
                        Penerbit anggota IKAPI yang dipercaya ribuan penulis di seluruh nusantara. Menerbitkan berbagai genre mulai dari Fiksi, Novel, hingga Buku Ajar dan Jurnal Ilmiah.
                        <br><br>
                        <strong>WhatsApp Admin & Redaksi:</strong> {{ config('services.whatsapp.number', '6285724693474') }}
                    </p>
                </div>

                <div class="mt-auto pt-4 flex items-center gap-2">
                    <img src="https://ik.imagekit.io/yqhp1cmbp/logo_megapress.svg" alt="Mega Press Logo" class="w-8 h-8 brightness-0">
                    <span class="font-serif font-bold text-2xl tracking-tight">Mega Press</span>
                </div>
            </div>

            {{-- Right Column --}}
            <div class="md:w-1/2 flex flex-col justify-between h-full min-h-[250px] md:items-end text-left md:text-right">
                <div class="flex flex-col md:items-end w-full">
                    <h2 class="font-serif text-[36px] md:text-[44px] leading-[1.05] tracking-tight mb-6 max-w-[550px]">
                        Siap menerbitkan karya terbaik Anda bersama kami?
                    </h2>

                    <a href="{{ route('home') }}#ajukan-naskah"
                       class="inline-block bg-[#DB8B00] text-[#171512] border-2 border-[#171512] text-sm font-bold px-6 py-3 rounded-full hover:bg-[#c97f00] transition-colors mb-10 self-start md:self-end shadow-[-4px_4px_0px_rgba(23,21,18,1)]">
                        Ajukan Naskah
                    </a>

                    <div class="flex flex-wrap gap-x-6 gap-y-3 text-[13px] font-bold opacity-90 justify-start md:justify-end mb-10 w-full">
                        <a href="{{ route('home') }}" class="hover:text-[#DB8B00] transition-colors">Home</a>
                        <a href="{{ route('bookstore.index') }}" class="hover:text-[#DB8B00] transition-colors">Mega Bookstore</a>
                        <a href="{{ route('blog.index') }}" class="hover:text-[#DB8B00] transition-colors">Blog</a>
                        <a href="{{ route('home') }}#layanan" class="hover:text-[#DB8B00] transition-colors">Layanan Penerbitan</a>
                    </div>
                </div>

                <div class="text-xs font-medium opacity-70 md:text-right flex flex-col items-start md:items-end w-full mt-auto">
                    <p class="mb-1">&copy; {{ date('Y') }} MEGA PRESS, All rights reserved.</p>
                    <p>Dibuat dan diterbitkan oleh Mega Press Nusantara</p>
                </div>
            </div>
        </div>
    </div>
</footer>
