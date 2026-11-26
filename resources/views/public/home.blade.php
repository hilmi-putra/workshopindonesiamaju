@extends('layouts.app')

@section('title', 'Workshop Indonesia Maju | Wujudkan Naskahmu Menjadi Buku yang Menginspirasi')
@section('meta_description', 'Penerbit indie terkemuka yang mendampingi penulis dari draf pertama hingga mejeng di WIM Bookstore.')

@section('content')
<div class="flex flex-col relative bg-[#F3F4F7] text-[#111218] min-h-screen">

    {{-- HERO SECTION: Architectural Editorial Monumental Headline + Perspective Paper Columns --}}
    <section class="relative overflow-hidden pt-20 sm:pt-28 md:pt-36 border-b border-[#C9CEDF] bg-[#F3F4F7]">
        {{-- Top Hero Sub-header Micro-Meta --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-6 sm:pb-8 flex items-center justify-between text-[10px] font-bold uppercase tracking-[0.2em] text-[#636778]">
            <span>Penerbit Independen</span>
            <span class="hidden sm:inline text-[#2B3288]">Ber-ISBN Resmi &bull; WIM Bookstore</span>
            <span>Indonesia</span>
        </div>

        {{-- Monumental Display Title (Yale Architectural Style) --}}
        <div class="max-w-6xl mx-auto px-4 sm:px-6 text-center pb-8 sm:pb-10 md:pb-12 relative z-10">
            <h1 class="font-serif text-[2.2rem] sm:text-6xl md:text-[5.2rem] font-normal text-[#111218] uppercase leading-[0.92] tracking-[-0.04em]">
                <span class="block">Wujudkan Naskahmu</span>
                <span class="block mt-1">
                    Menjadi <em class="italic font-normal lowercase text-[#2B3288] tracking-[-0.02em] px-1">buku yang</em>
                </span>
                <span class="block mt-1">Menginspirasi</span>
            </h1>
        </div>

        {{-- Perspective Repeating Typography Columns Band + Centered Statement --}}
        <div class="relative border-t border-[#C9CEDF] bg-[#EAECEF]/70 py-14 md:py-20 overflow-hidden">
            {{-- Repeating Perspective Manuscript Text Wallpaper --}}
            <div aria-hidden="true" class="absolute inset-0 pointer-events-none select-none overflow-hidden opacity-[0.11]">
                <div class="grid grid-cols-3 sm:grid-cols-6 lg:grid-cols-9 gap-3 -mx-6 h-full text-[9px] leading-[1.35] font-medium text-[#2B3288] tracking-tight">
                    @for ($col = 0; $col < 9; $col++)
                        <div class="py-2 {{ $col % 2 === 0 ? 'skew-y-2' : '-skew-y-2' }}">
                            @for ($rep = 0; $rep < 8; $rep++)
                                <p class="mb-2">
                                    Workshop Indonesia Maju mendampingi penulis dari draf pertama hingga terbit ber-ISBN resmi dan hadir di WIM Bookstore.
                                </p>
                            @endfor
                        </div>
                    @endfor
                </div>
            </div>

            {{-- Centered Focal Statement Box --}}
            <div class="max-w-xl mx-auto px-4 sm:px-6 relative z-10 text-center">
                <div class="bg-[#F3F4F7]/95 backdrop-blur-xs border border-[#C9CEDF] px-6 py-8 sm:px-10 sm:py-10">
                    <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-[#2B3288] mb-3">
                        Workshop Indonesia Maju
                    </p>
                    <p class="text-sm sm:text-base text-[#111218]/85 leading-relaxed font-medium mb-7">
                        Penerbit indie terkemuka yang mendampingi penulis dari draf pertama hingga terbit ber-ISBN dan mejeng di WIM Bookstore.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
                        <a href="#ajukan-naskah"
                           class="inline-flex items-center justify-center gap-2 h-12 px-7 w-full sm:w-auto bg-[#2B3288] hover:bg-[#1F256D] text-white font-bold text-xs uppercase tracking-[0.15em] transition-colors">
                            <span>Ajukan Naskah Sekarang</span>
                            <span>&rarr;</span>
                        </a>
                        <a href="{{ route('bookstore.index') }}"
                           class="inline-flex items-center justify-center h-12 px-7 w-full sm:w-auto bg-white text-[#111218] border border-[#111218]/25 hover:border-[#2B3288] hover:text-[#2B3288] text-xs font-bold uppercase tracking-[0.15em] transition-colors">
                            Jelajahi Bookstore
                        </a>
                    </div>
                </div>
            </div>

            {{-- Bottom Corner Architectural Micro-Labels --}}
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 relative z-10 flex items-center justify-between text-[10px] font-bold uppercase tracking-[0.18em] text-[#111218]/70">
                <span>Editorial &amp; Kurasi</span>
                <span class="hidden sm:inline">IKAPI Official Member</span>
                <span>WIM Bookstore</span>
            </div>
        </div>
    </section>

    {{-- STATS & EDITORIAL DIRECTORY INDEX (Reference Rows 2) --}}
    <section class="border-b border-[#C9CEDF] bg-[#F3F4F7] relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12">
                {{-- Left Column: Core Metrics --}}
                <div class="lg:col-span-5 py-12 lg:py-16 lg:pr-10 lg:border-r border-[#C9CEDF] flex flex-col justify-between gap-8">
                    <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#2B3288]">Statistik Penerbitan</p>
                    <div class="grid grid-cols-3 gap-4 border-t border-b border-[#C9CEDF] py-6">
                        <div>
                            <p class="font-serif text-3xl sm:text-4xl font-bold text-[#2B3288]">500+</p>
                            <p class="text-[11px] text-[#636778] mt-1 font-semibold uppercase tracking-wider">Penulis Bergabung</p>
                        </div>
                        <div class="border-l border-[#C9CEDF] pl-4">
                            <p class="font-serif text-3xl sm:text-4xl font-bold text-[#111218]">1.2K+</p>
                            <p class="text-[11px] text-[#636778] mt-1 font-semibold uppercase tracking-wider">Buku Diterbitkan</p>
                        </div>
                        <div class="border-l border-[#C9CEDF] pl-4">
                            <p class="font-serif text-3xl sm:text-4xl font-bold text-[#2B3288]">100%</p>
                            <p class="text-[11px] text-[#636778] mt-1 font-semibold uppercase tracking-wider">Ber-ISBN Legal</p>
                        </div>
                    </div>
                    <p class="text-xs text-[#636778] leading-relaxed">
                        Didukung penuh untuk penulis fiksi, akademik, buku ajar perguruan tinggi, hingga karya referensi nasional.
                    </p>
                </div>

                {{-- Right Column: Yale-style Numbered Index List --}}
                <div class="lg:col-span-7 py-12 lg:py-16 lg:pl-12">
                    <div class="divide-y divide-[#C9CEDF] border-t border-b border-[#C9CEDF]">
                        @php
                            $tocLinks = [
                                ['label' => 'TENTANG WORKSHOP INDONESIA MAJU', 'no' => '01', 'href' => '#tentang'],
                                ['label' => 'LAYANAN PENERBITAN PROFESIONAL', 'no' => '02', 'href' => '#layanan'],
                                ['label' => 'ALUR PROSES PENERBITAN', 'no' => '03', 'href' => '#publishing'],
                                ['label' => 'KATALOG BUKU PILIHAN', 'no' => '04', 'href' => '#buku-pilihan'],
                                ['label' => 'PERTANYAAN UMUM (FAQ)', 'no' => '05', 'href' => '#faq'],
                                ['label' => 'AJUKAN NASKAH SEKARANG', 'no' => '06', 'href' => '#ajukan-naskah'],
                            ];
                        @endphp
                        @foreach($tocLinks as $item)
                            <a href="{{ $item['href'] }}"
                               class="group py-3.5 flex items-center justify-between text-base sm:text-lg font-medium tracking-tight text-[#111218] hover:text-[#2B3288] transition-colors">
                                <span class="group-hover:translate-x-1.5 transition-transform">{{ $item['label'] }}</span>
                                <span class="font-sans text-sm sm:text-base font-medium text-[#C5CAE0] group-hover:text-[#2B3288] transition-colors">{{ $item['no'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ABOUT SECTION (#tentang): Stacked Lavender-Indigo Book Spines + Hairline Ledger --}}
    <section id="tentang" class="py-16 md:py-24 border-b border-[#C9CEDF] relative z-10 scroll-mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Architectural Section Top Bar --}}
            <div class="flex items-center justify-between border-t border-[#111218]/40 pt-3 pb-10 text-xs font-bold uppercase tracking-[0.18em]">
                <span class="text-[#111218]">01</span>
                <span class="text-[#2B3288]">Tentang Workshop Indonesia Maju</span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                {{-- Left Column: Bespoke Architectural Editorial Books Artwork + Author Testimonials --}}
                <div class="lg:col-span-5 space-y-6">
                    {{-- Bespoke Studio Hardcover Composition --}}
                    <div class="bg-[#EAECEF] border border-[#C9CEDF] overflow-hidden">
                        <div class="aspect-[4/3] w-full overflow-hidden bg-[#DDE2F3]">
                            <img src="{{ asset('images/wim-about-books.jpg') }}"
                                 alt="Koleksi Terbitan Workshop Indonesia Maju"
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="px-4 py-3 border-t border-[#C9CEDF] bg-white flex items-center justify-between text-[10px] font-bold uppercase tracking-[0.16em] text-[#636778]">
                            <span>IKAPI Member &bull; Est. 2023</span>
                            <span class="text-[#2B3288]">Fiksi, Akademik &amp; Jurnal</span>
                        </div>
                    </div>

                    {{-- Author Quotes --}}
                    <div class="space-y-4">
                        <div class="bg-white border border-[#C9CEDF] p-6">
                            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#2B3288] mb-2">Kutipan Penulis</p>
                            <p class="font-serif text-base italic text-[#111218] leading-relaxed">
                                "Proses penerbitan di Workshop Indonesia Maju sangat transparan. Editornya suportif dalam menyempurnakan naskah saya hingga tampil premium."
                            </p>
                            <p class="text-xs font-bold text-[#2B3288] mt-3">&mdash; Ahmad Rizky, Penulis Novel</p>
                        </div>
                        <div class="bg-white border border-[#C9CEDF] p-6">
                            <p class="font-serif text-base italic text-[#111218] leading-relaxed">
                                "Desain sampul dan tata letaknya memikat pembaca. Buku ajar kampus kami langsung terserap dengan cepat."
                            </p>
                            <p class="text-xs font-bold text-[#111218] mt-3">&mdash; Siti Nurbaya, Penulis Non-Fiksi</p>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Editorial Headline + Key-Value Hairline Ledger --}}
                <div class="lg:col-span-7 lg:pl-6 space-y-8">
                    <div>
                        <h2 class="font-serif text-3xl sm:text-5xl text-[#111218] leading-[1.05] tracking-[-0.03em] mb-5">
                            Satu Penerbit, <em class="italic font-normal text-[#2B3288]">Ribuan Cerita Penting.</em>
                        </h2>
                    </div>

                    {{-- Hairline Ledger Rows (Matches bottom-right of reference mockup) --}}
                    <div class="border-t border-[#111218]/30 divide-y divide-[#C9CEDF]">
                        <div class="py-5 grid grid-cols-1 sm:grid-cols-12 gap-3">
                            <span class="sm:col-span-4 text-[11px] font-bold uppercase tracking-[0.16em] text-[#111218]">PROFIL PENERBIT</span>
                            <p class="sm:col-span-8 text-sm text-[#636778] leading-relaxed">
                                Berdiri sejak 2023, Workshop Indonesia Maju hadir sebagai rumah bagi penulis fiksi, buku ajar akademik, referensi ilmiah, hingga memoar personal. Kami memadukan kurasi editorial teliti dengan distribusi modern ke pembaca di seluruh Indonesia.
                            </p>
                        </div>
                        <div class="py-5 grid grid-cols-1 sm:grid-cols-12 gap-3">
                            <span class="sm:col-span-4 text-[11px] font-bold uppercase tracking-[0.16em] text-[#2B3288]">PENDAMPINGAN PENUH</span>
                            <p class="sm:col-span-8 text-sm text-[#636778] leading-relaxed">
                                Pendampingan intensif mulai dari draf mentah, penyuntingan EYD, tata letak isi, hingga buku siap edar secara resmi.
                            </p>
                        </div>
                        <div class="py-5 grid grid-cols-1 sm:grid-cols-12 gap-3">
                            <span class="sm:col-span-4 text-[11px] font-bold uppercase tracking-[0.16em] text-[#2B3288]">CETAK &amp; DISTRIBUSI</span>
                            <p class="sm:col-span-8 text-sm text-[#636778] leading-relaxed">
                                Buku tampil langsung di katalog resmi WIM Bookstore dengan alur pemesanan praktis via keranjang maupun WhatsApp.
                            </p>
                        </div>
                        <div class="py-5 grid grid-cols-1 sm:grid-cols-12 gap-3">
                            <span class="sm:col-span-4 text-[11px] font-bold uppercase tracking-[0.16em] text-[#111218]">LEGALITAS RESMI</span>
                            <p class="sm:col-span-8 text-sm text-[#636778] leading-relaxed">
                                Pengurusan ISBN resmi Perpusnas dan pencatatan hak terbit yang aman serta transparan bagi penulis maupun akademisi.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SERVICES SECTION (#layanan) --}}
    <section id="layanan" class="py-16 md:py-24 bg-white border-b border-[#C9CEDF] relative z-10 scroll-mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between border-t border-[#111218]/40 pt-3 pb-10 text-xs font-bold uppercase tracking-[0.18em]">
                <span class="text-[#111218]">02</span>
                <span class="text-[#2B3288]">Layanan Kami</span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-end mb-12">
                <div class="lg:col-span-8">
                    <h3 class="font-serif text-3xl sm:text-5xl text-[#111218] leading-[1.02] tracking-[-0.03em]">
                        Solusi Lengkap <em class="italic font-normal text-[#2B3288]">untuk</em> Penulis
                    </h3>
                </div>
                <div class="lg:col-span-4">
                    <p class="text-sm text-[#636778] leading-relaxed">
                        Mulai dari penyuntingan hingga royalti, kami memfasilitasi setiap aspek penerbitan Anda.
                    </p>
                </div>
            </div>

            @php
                $services = [
                    ['no' => '01', 'title' => 'Penyuntingan Profesional', 'desc' => 'Editor berpengalaman menyempurnakan EYD, alur, dan keterbacaan naskah tanpa menghilangkan ciri khas penulis.', 'img' => asset('images/wim-service-editing.jpg')],
                    ['no' => '02', 'title' => 'Desain Sampul & Tata Letak', 'desc' => 'Desain sampul eksklusif dan layout isi standar cetak penerbitan nasional.', 'img' => asset('images/wim-service-design.jpg')],
                    ['no' => '03', 'title' => 'ISBN & Distribusi Nasional', 'desc' => 'Pengurusan ISBN Perpusnas resmi serta distribusi ke WIM Bookstore.', 'img' => asset('images/wim-service-distribution.jpg')],
                    ['no' => '04', 'title' => 'Royalti Transparan', 'desc' => 'Skema royalti kompetitif 10–15% dan pelaporan penjualan yang jelas.', 'img' => asset('images/wim-service-royalty.jpg')],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 border-t border-l border-[#C9CEDF]">
                @foreach($services as $svc)
                    <div class="p-8 md:p-10 border-r border-b border-[#C9CEDF] bg-[#F3F4F7]/50 hover:bg-white transition-colors flex flex-col sm:flex-row items-center justify-between gap-6">
                        <div class="w-full sm:w-3/5 text-left">
                            <span class="inline-block text-[11px] font-bold uppercase tracking-[0.2em] text-[#2B3288] mb-2">
                                Layanan // {{ $svc['no'] }}
                            </span>
                            <h4 class="text-2xl font-serif font-bold mb-3 text-[#111218] leading-tight">{{ $svc['title'] }}</h4>
                            <p class="text-sm text-[#636778] leading-relaxed mb-5">{{ $svc['desc'] }}</p>
                            <a href="#ajukan-naskah" class="inline-flex items-center gap-2 text-[#2B3288] font-bold text-xs uppercase tracking-[0.15em] hover:underline">
                                <span>Ajukan Sekarang</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                        <div class="w-full sm:w-2/5 aspect-[4/3] sm:h-40 flex justify-center items-center bg-[#E7EBF7] border border-[#C9CEDF] overflow-hidden shrink-0">
                            <img src="{{ $svc['img'] }}" alt="{{ $svc['title'] }}" class="w-full h-full object-cover">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- PUBLISHING PROCESS SECTION (#publishing) --}}
    <section id="publishing" class="py-16 md:py-24 bg-[#E7EBF7] border-b border-[#C9CEDF] relative z-10 scroll-mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between border-t border-[#111218]/40 pt-3 pb-10 text-xs font-bold uppercase tracking-[0.18em]">
                <span class="text-[#111218]">03</span>
                <span class="text-[#2B3288]">Alur Kerja Sama</span>
            </div>

            <div class="mb-12">
                <h3 class="font-serif text-3xl sm:text-5xl text-[#111218] tracking-[-0.03em]">
                    5 Langkah Mudah <em class="italic font-normal text-[#2B3288]">Terbitkan</em> Bukumu
                </h3>
            </div>

            @php
                $steps = [
                    ['no' => '01', 'title' => 'Kirim Naskah', 'desc' => 'Unggah draf naskah dan sinopsis melalui formulir di bawah.'],
                    ['no' => '02', 'title' => 'Kurasi Redaksi', 'desc' => 'Tim editor menilai kelayakan dan potensi pasar naskah.'],
                    ['no' => '03', 'title' => 'Kontrak & Editing', 'desc' => 'Kesepakatan penerbitan dan penyuntingan naskah.'],
                    ['no' => '04', 'title' => 'Desain & ISBN', 'desc' => 'Pembuatan cover, layouting halaman, dan pengurusan ISBN.'],
                    ['no' => '05', 'title' => 'Terbit & Jual', 'desc' => 'Buku dicetak dan tampil di katalog WIM Bookstore.'],
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 border-t border-l border-[#C9CEDF] bg-white">
                @foreach($steps as $st)
                    <div class="border-r border-b border-[#C9CEDF] p-6 flex flex-col justify-between hover:bg-[#F3F4F7] transition-colors">
                        <div>
                            <span class="font-serif text-4xl font-bold text-[#2B3288] block mb-4">{{ $st['no'] }}</span>
                            <h4 class="font-bold text-sm uppercase tracking-wider text-[#111218] mb-2">{{ $st['title'] }}</h4>
                            <p class="text-xs text-[#636778] leading-relaxed">{{ $st['desc'] }}</p>
                        </div>
                        <div class="pt-5 mt-5 border-t border-[#E3E7F3] text-[10px] font-bold uppercase tracking-[0.18em] text-[#636778]">
                            Tahap {{ $st['no'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FEATURED BOOKS SHOWCASE --}}
    @if($featuredBooks->isNotEmpty())
        <section id="buku-pilihan" class="py-16 md:py-24 bg-[#F3F4F7] border-b border-[#C9CEDF] relative z-10 scroll-mt-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between border-t border-[#111218]/40 pt-3 pb-10 text-xs font-bold uppercase tracking-[0.18em]">
                    <span class="text-[#111218]">04</span>
                    <span class="text-[#2B3288]">Katalog Terbitan</span>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-12">
                    <div>
                        <h3 class="font-serif text-3xl sm:text-5xl text-[#111218] tracking-[-0.03em]">
                            Buku Pilihan <em class="italic font-normal text-[#2B3288]">WIM Bookstore</em>
                        </h3>
                    </div>
                    <a href="{{ route('bookstore.index') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 border border-[#2B3288] text-xs font-bold uppercase tracking-[0.14em] text-[#2B3288] hover:bg-[#2B3288] hover:text-white transition-colors self-start sm:self-auto">
                        <span>Lihat Semua Buku</span>
                        <span>&rarr;</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($featuredBooks->take(4) as $book)
                        <x-book-card :book="$book" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- FAQ SECTION --}}
    <section id="faq" class="py-16 md:py-24 bg-white border-b border-[#C9CEDF] relative z-10 scroll-mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between border-t border-[#111218]/40 pt-3 pb-10 text-xs font-bold uppercase tracking-[0.18em]">
                <span class="text-[#111218]">05</span>
                <span class="text-[#2B3288]">FAQ Redaksi</span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                <div class="lg:col-span-5">
                    <h3 class="font-serif text-3xl sm:text-4xl text-[#111218] tracking-[-0.03em] leading-tight">
                        Pertanyaan yang <em class="italic font-normal text-[#2B3288]">Sering Diajukan</em>
                    </h3>
                    <p class="text-sm text-[#636778] mt-3 leading-relaxed">
                        Panduan ringkas mengenai proses pengajuan naskah, kurasi, dan pemesanan buku di WIM Bookstore.
                    </p>
                </div>

                <div class="lg:col-span-7">
                    @php
                        $faqs = [
                            ['q' => 'Berapa lama proses evaluasi naskah?', 'a' => 'Normalnya memakan waktu 2–4 minggu kerja sejak naskah diterima oleh tim redaksi kami.'],
                            ['q' => 'Genre buku apa saja yang diterima?', 'a' => 'Kami menerima fiksi (novel, kumpulan cerpen), non-fiksi, buku ajar/akademik, pengembangan diri, dan referensi ilmiah.'],
                            ['q' => 'Bagaimana cara membeli buku di WIM Bookstore?', 'a' => 'Pilih buku yang Anda inginkan, masukkan ke keranjang atau klik "Beli via WhatsApp", lalu isi nama dan alamat singkat untuk terhubung langsung dengan admin kami.'],
                        ];
                    @endphp
                    <div class="border-t border-b border-[#111218]/30 divide-y divide-[#C9CEDF]" x-data="{ openIdx: 0 }">
                        @foreach($faqs as $idx => $faq)
                            <div>
                                <button type="button" @click="openIdx = openIdx === {{ $idx }} ? null : {{ $idx }}"
                                        class="w-full py-5 text-left font-bold text-sm sm:text-base text-[#111218] hover:text-[#2B3288] flex justify-between items-center gap-4 cursor-pointer transition-colors">
                                    <span class="flex items-center gap-4">
                                        <span class="text-xs font-bold text-[#2B3288]">0{{ $idx + 1 }}</span>
                                        <span>{{ $faq['q'] }}</span>
                                    </span>
                                    <span class="text-[#2B3288] text-xl shrink-0" x-text="openIdx === {{ $idx }} ? '−' : '+'"></span>
                                </button>
                                <div x-show="openIdx === {{ $idx }}" x-cloak class="pb-5 pl-8 text-sm text-[#636778] leading-relaxed">
                                    {{ $faq['a'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- MANUSCRIPT SUBMISSION FORM (#ajukan-naskah) --}}
    <section id="ajukan-naskah" class="py-16 md:py-24 bg-[#F3F4F7] relative z-10 scroll-mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between border-t border-[#111218]/40 pt-3 pb-10 text-xs font-bold uppercase tracking-[0.18em]">
                <span class="text-[#111218]">06</span>
                <span class="text-[#2B3288]">Ajukan Naskah</span>
            </div>

            <div class="bg-white border border-[#C9CEDF] overflow-hidden grid grid-cols-1 lg:grid-cols-12">
                <div class="lg:col-span-4 p-8 sm:p-10 bg-[#E7EBF7] border-b lg:border-b-0 lg:border-r border-[#C9CEDF] flex flex-col justify-between space-y-6">
                    <div>
                        <div class="size-14 bg-white border border-[#C9CEDF] p-2 mb-6 flex items-center justify-center">
                            <img src="{{ asset('wim.png') }}" alt="Logo Workshop Indonesia Maju" class="w-full h-full object-contain">
                        </div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#2B3288] mb-2">Formulir Redaksi</p>
                        <h2 class="font-serif text-3xl text-[#111218] leading-tight mb-4">
                            Mulai Langkah Pertama Bersama <em class="italic font-normal text-[#2B3288]">Workshop Indonesia Maju</em>
                        </h2>
                        <p class="text-sm text-[#636778] leading-relaxed">
                            Unggah naskah Anda (PDF/DOC/DOCX, maks 10 MB). Tim editor kami akan meninjau dan menghubungi Anda melalui email atau WhatsApp.
                        </p>
                    </div>
                    <div class="pt-6 border-t border-[#C9CEDF] text-[11px] text-[#636778] space-y-1">
                        <p class="font-bold uppercase tracking-wider text-[#111218]">Kerahasiaan Naskah</p>
                        <p>Setiap file naskah dijamin kerahasiaannya oleh tim redaksi Workshop Indonesia Maju.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('submissions.store') }}" enctype="multipart/form-data" class="lg:col-span-8 p-8 sm:p-10 space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <x-input name="title" label="Judul Naskah" placeholder="Misal: Senja di Balik Aksara" required />
                        <x-input name="author_name" label="Nama Penulis" :value="auth()->user()?->name" placeholder="Nama lengkap penulis" required />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <x-input type="email" name="email" label="Email" :value="auth()->user()?->email" placeholder="penulis@email.com" required />
                        <x-input type="tel" name="phone" label="No. WhatsApp" :value="auth()->user()?->phone" placeholder="0812xxxxxxxx" required />
                        <x-input name="genre" label="Kategori / Genre" placeholder="Novel, Buku Ajar, Non-Fiksi..." />
                    </div>

                    <div>
                        <label for="synopsis" class="block text-xs font-bold uppercase tracking-wider text-[#111218] mb-1.5">
                            Sinopsis Singkat <span class="text-red-500">*</span>
                        </label>
                        <textarea id="synopsis" name="synopsis" rows="4" required
                                  placeholder="Jelaskan garis besar naskah Anda, target pembaca, dan keunikannya..."
                                  class="w-full rounded-xl border border-[#C9CEDF] bg-white px-4 py-3 text-sm text-[#111218] focus:outline-none focus:border-[#2B3288] focus:ring-2 focus:ring-[#2B3288]/15">{{ old('synopsis') }}</textarea>
                        @error('synopsis') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="manuscript_file" class="block text-xs font-bold uppercase tracking-wider text-[#111218] mb-1.5">
                            File Naskah (PDF, DOC, DOCX, maks 10 MB) <span class="text-red-500">*</span>
                        </label>
                        <input id="manuscript_file" type="file" name="manuscript_file" accept=".pdf,.doc,.docx" required
                               class="w-full rounded-xl border border-dashed border-[#C9CEDF] bg-[#F3F4F7] px-4 py-3 text-sm text-[#111218] file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-bold file:uppercase file:tracking-wider file:bg-[#2B3288] file:text-white hover:file:bg-[#1F256D]">
                        @error('manuscript_file') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-3 flex flex-col sm:flex-row gap-3 items-center justify-between border-t border-[#E3E7F3]">
                        <p class="text-xs text-[#636778]">Evaluasi redaksi maksimal 2–4 minggu kerja.</p>
                        <x-button type="submit" variant="primary" size="square" class="w-full sm:w-auto">
                            Kirim Naskah &rarr;
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- NEWSLETTER BAND --}}
    <section class="pb-16 md:pb-20 bg-[#F3F4F7] relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#2B3288] text-white border border-[#1F256D] p-8 sm:p-12 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                <div class="max-w-xl">
                    <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-white/65 mb-2">Buletin Resmi WIM</p>
                    <h3 class="font-serif text-2xl sm:text-4xl leading-tight">
                        Dapatkan Info Terbitan &amp; Promo Terbaru
                    </h3>
                    <p class="text-white/75 text-sm mt-2">Berlangganan newsletter resmi Workshop Indonesia Maju tanpa spam.</p>
                </div>
                <form method="POST" action="{{ route('newsletter.subscribe') }}" class="flex w-full lg:w-auto gap-0 border border-white/30 bg-white">
                    @csrf
                    <input type="email" name="email" required placeholder="Email Anda..."
                           class="flex-1 lg:w-72 h-12 px-4 text-sm bg-white text-[#111218] focus:outline-none placeholder:text-gray-400">
                    <button type="submit" class="h-12 px-6 bg-[#111218] hover:bg-[#1F256D] text-white font-bold text-xs uppercase tracking-[0.15em] cursor-pointer shrink-0 transition-colors">
                        Langganan
                    </button>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
