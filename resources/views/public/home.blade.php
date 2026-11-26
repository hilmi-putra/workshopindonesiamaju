@extends('layouts.app')

@section('title', 'Mega Press | Wujudkan Naskahmu Menjadi Buku yang Menginspirasi')
@section('meta_description', 'Penerbit indie terkemuka yang mendampingi penulis dari draf pertama hingga mejeng di Mega Bookstore.')

@section('content')
<div class="flex flex-col relative bg-[#FAF8F4] min-h-screen">
    {{-- Global Soft Grid Background --}}
    <div class="absolute inset-0 pointer-events-none opacity-[0.03] z-0"
         style="background-image: url(&quot;data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23000000' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E&quot;); background-repeat: repeat;">
    </div>

    {{-- HERO SECTION --}}
    <section class="relative overflow-hidden pt-32 pb-20 md:pt-44 md:pb-28 min-h-[85vh] flex items-center z-10">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-[#DB8B00]/10 blur-[120px] rounded-full pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[300px] bg-amber-500/10 blur-[100px] rounded-full pointer-events-none"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center flex flex-col items-center relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 backdrop-blur-sm border border-[#DB8B00]/20 text-[#DB8B00] mb-8 shadow-sm">
                <span class="flex h-2 w-2 rounded-full bg-[#DB8B00] animate-pulse"></span>
                <span class="text-xs font-semibold tracking-wider uppercase">Penerbit Independen Terpercaya</span>
            </div>

            <h1 class="font-serif text-4xl sm:text-5xl md:text-6xl font-bold text-[#171512] leading-[1.1] mb-6 max-w-3xl">
                Wujudkan Naskahmu Menjadi Buku yang <em class="italic font-light text-[#DB8B00]">Menginspirasi</em>
            </h1>

            <p class="text-base sm:text-lg text-[#171512]/70 max-w-2xl mb-10 leading-relaxed">
                Penerbit indie terkemuka yang mendampingi penulis dari draf pertama hingga terbit ber-ISBN dan mejeng di Mega Bookstore.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center w-full sm:w-auto">
                <a href="#ajukan-naskah"
                   class="inline-flex items-center justify-center rounded-full px-8 h-14 w-full sm:w-auto bg-[#DB8B00] hover:bg-[#c07a00] text-white font-semibold text-base shadow-xl shadow-[#DB8B00]/20 transition-all hover:-translate-y-0.5">
                    Ajukan Naskah Sekarang &rarr;
                </a>
                <a href="{{ route('bookstore.index') }}"
                   class="inline-flex items-center justify-center rounded-full px-8 h-14 w-full sm:w-auto bg-white/70 backdrop-blur-sm text-[#171512] border border-[#DDD8C8] hover:bg-white text-base font-semibold transition-all hover:-translate-y-0.5">
                    Jelajahi Bookstore
                </a>
            </div>
        </div>
    </section>

    {{-- LOGO / STATS MARQUEE --}}
    <section class="py-10 border-y border-[#DDD8C8]/70 bg-white/50 relative z-10 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 grid grid-cols-3 gap-6 text-center">
            <div>
                <p class="font-serif text-3xl sm:text-4xl font-bold text-[#6E0000]">500+</p>
                <p class="text-xs sm:text-sm text-[#6B6860] mt-1 font-medium">Penulis Bergabung</p>
            </div>
            <div>
                <p class="font-serif text-3xl sm:text-4xl font-bold text-[#DB8B00]">1.2K+</p>
                <p class="text-xs sm:text-sm text-[#6B6860] mt-1 font-medium">Buku Diterbitkan</p>
            </div>
            <div>
                <p class="font-serif text-3xl sm:text-4xl font-bold text-[#084c3c]">100%</p>
                <p class="text-xs sm:text-sm text-[#6B6860] mt-1 font-medium">Ber-ISBN Resmi &amp; Legal</p>
            </div>
        </div>
    </section>

    {{-- ABOUT SECTION --}}
    <section id="tentang" class="py-20 md:py-28 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <p class="text-xs font-bold tracking-[0.2em] uppercase text-[#DB8B00] mb-3">Tentang Mega Press</p>
                <h2 class="font-serif text-3xl sm:text-4xl md:text-5xl text-[#171512] leading-tight mb-6">
                    Satu Penerbit, <em class="italic font-light text-[#6E0000]">Ribuan Cerita Penting.</em>
                </h2>
                <p class="text-[#6B6860] text-base leading-relaxed mb-6">
                    Berdiri sejak 2023, Mega Press Nusantara hadir sebagai rumah bagi penulis fiksi, buku ajar akademik, referensi ilmiah, hingga memoar personal. Kami memadukan kurasi editorial teliti dengan distribusi modern ke pembaca di seluruh Indonesia.
                </p>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 rounded-2xl bg-white border border-[#DDD8C8]">
                        <p class="font-bold text-[#171512] text-sm">Pendampingan Penuh</p>
                        <p class="text-xs text-[#6B6860] mt-1">Dari draf mentah hingga buku siap edar.</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-white border border-[#DDD8C8]">
                        <p class="font-bold text-[#171512] text-sm">Cetak &amp; Distribusi</p>
                        <p class="text-xs text-[#6B6860] mt-1">Tersedia di Mega Bookstore &amp; pemesanan WA.</p>
                    </div>
                </div>
            </div>
            <div class="bg-[#EFEADD] rounded-[2rem] p-8 sm:p-10 border border-[#DDD8C8] space-y-5">
                <div class="bg-white rounded-2xl p-6 border border-[#DDD8C8] shadow-xs">
                    <p class="text-xs font-bold uppercase tracking-wider text-[#DB8B00] mb-2">Kutipan Penulis</p>
                    <p class="font-serif text-lg italic text-[#171512] leading-relaxed">
                        "Proses penerbitan di Mega Press sangat transparan. Editornya suportif dalam menyempurnakan naskah saya hingga tampil premium."
                    </p>
                    <p class="text-xs font-bold text-[#6E0000] mt-3">— Ahmad Rizky, Penulis Novel</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-[#DDD8C8] shadow-xs">
                    <p class="font-serif text-lg italic text-[#171512] leading-relaxed">
                        "Desain sampul dan tata letaknya memikat pembaca. Buku ajar kampus kami langsung terserap dengan cepat."
                    </p>
                    <p class="text-xs font-bold text-[#084c3c] mt-3">— Siti Nurbaya, Penulis Non-Fiksi</p>
                </div>
            </div>
        </div>
    </section>

    {{-- SERVICES SECTION --}}
    <section id="layanan" class="py-20 md:py-28 relative z-10 scroll-mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-xs font-bold tracking-[0.2em] uppercase mb-3 text-[#DB8B00]">Layanan Kami</h2>
                <h3 class="font-serif text-3xl sm:text-5xl text-[#6E0000] leading-tight mb-4">
                    Solusi Lengkap untuk <em class="italic font-light text-[#DB8B00]">Penulis</em>
                </h3>
                <p class="text-[#6E0000]/70 text-base sm:text-lg">
                    Mulai dari penyuntingan hingga royalti, kami memfasilitasi setiap aspek penerbitan Anda.
                </p>
            </div>

            @php
                $services = [
                    ['title' => 'Penyuntingan Profesional', 'desc' => 'Editor berpengalaman menyempurnakan EYD, alur, dan keterbacaan naskah tanpa menghilangkan ciri khas penulis.', 'img' => 'https://ik.imagekit.io/yqhp1cmbp/megapress/penyuntingan.svg'],
                    ['title' => 'Desain Sampul & Tata Letak', 'desc' => 'Desain sampul eksklusif dan layout isi standar cetak penerbitan nasional.', 'img' => 'https://ik.imagekit.io/yqhp1cmbp/megapress/design.svg'],
                    ['title' => 'ISBN & Distribusi Nasional', 'desc' => 'Pengurusan ISBN Perpusnas resmi serta distribusi ke Mega Bookstore.', 'img' => 'https://ik.imagekit.io/yqhp1cmbp/megapress/distribusi.svg'],
                    ['title' => 'Royalti Transparan', 'desc' => 'Skema royalti kompetitif 10–15% dan pelaporan penjualan yang jelas.', 'img' => 'https://ik.imagekit.io/yqhp1cmbp/megapress/royalti.svg'],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($services as $svc)
                    <div class="p-8 md:p-10 rounded-3xl border border-[#6E0000]/15 bg-white/50 hover:bg-white transition-all flex flex-col sm:flex-row items-center justify-between gap-6">
                        <div class="w-full sm:w-3/5 text-center sm:text-left">
                            <h4 class="text-2xl font-serif font-bold mb-3 text-[#6E0000]">{{ $svc['title'] }}</h4>
                            <p class="text-sm text-[#6B6860] leading-relaxed mb-4">{{ $svc['desc'] }}</p>
                            <a href="#ajukan-naskah" class="inline-flex items-center text-[#DB8B00] font-bold text-sm hover:text-[#6E0000]">
                                Ajukan Sekarang &rarr;
                            </a>
                        </div>
                        <div class="w-full sm:w-2/5 flex justify-center h-36">
                            <img src="{{ $svc['img'] }}" alt="{{ $svc['title'] }}" class="max-h-full object-contain">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- PUBLISHING PROCESS SECTION --}}
    <section id="publishing" class="py-20 md:py-28 bg-[#EFEADD]/60 border-y border-[#DDD8C8] relative z-10 scroll-mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <p class="text-xs font-bold tracking-[0.2em] uppercase text-[#DB8B00] mb-3">Alur Kerja Sama</p>
                <h3 class="font-serif text-3xl sm:text-4xl text-[#171512]">5 Langkah Mudah Terbitkan Bukumu</h3>
            </div>

            @php
                $steps = [
                    ['no' => '01', 'title' => 'Kirim Naskah', 'desc' => 'Unggah draf naskah dan sinopsis melalui formulir di bawah.'],
                    ['no' => '02', 'title' => 'Kurasi Redaksi', 'desc' => 'Tim editor menilai kelayakan dan potensi pasar naskah.'],
                    ['no' => '03', 'title' => 'Kontrak & Editing', 'desc' => 'Kesepakatan penerbitan dan penyuntingan naskah.'],
                    ['no' => '04', 'title' => 'Desain & ISBN', 'desc' => 'Pembuatan cover, layouting halaman, dan pengurusan ISBN.'],
                    ['no' => '05', 'title' => 'Terbit & Jual', 'desc' => 'Buku dicetak dan tampil di katalog Mega Bookstore.'],
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
                @foreach($steps as $st)
                    <div class="bg-white rounded-2xl border border-[#DDD8C8] p-6 flex flex-col">
                        <span class="font-serif text-3xl font-bold text-[#DB8B00] mb-3">{{ $st['no'] }}</span>
                        <h4 class="font-bold text-base text-[#171512] mb-2">{{ $st['title'] }}</h4>
                        <p class="text-xs text-[#6B6860] leading-relaxed">{{ $st['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FEATURED BOOKS SHOWCASE --}}
    @if($featuredBooks->isNotEmpty())
        <section class="py-20 md:py-28 relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-14">
                    <div>
                        <p class="text-xs font-bold tracking-[0.2em] uppercase text-[#DB8B00] mb-2">Katalog Terbitan</p>
                        <h3 class="font-serif text-3xl sm:text-4xl text-[#171512]">Buku Pilihan Mega Bookstore</h3>
                    </div>
                    <a href="{{ route('bookstore.index') }}" class="text-sm font-bold text-[#DB8B00] hover:text-[#6E0000]">
                        Lihat Semua Buku &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-12">
                    @foreach($featuredBooks->take(4) as $book)
                        <x-book-card :book="$book" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- FAQ SECTION --}}
    <section class="py-16 md:py-24 relative z-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-10">
                <p class="text-xs font-bold tracking-[0.2em] uppercase text-[#DB8B00] mb-2">FAQ</p>
                <h3 class="font-serif text-3xl text-[#171512]">Pertanyaan yang Sering Diajukan</h3>
            </div>
            @php
                $faqs = [
                    ['q' => 'Berapa lama proses evaluasi naskah?', 'a' => 'Normalnya memakan waktu 2–4 minggu kerja sejak naskah diterima oleh tim redaksi kami.'],
                    ['q' => 'Genre buku apa saja yang diterima?', 'a' => 'Kami menerima fiksi (novel, kumpulan cerpen), non-fiksi, buku ajar/akademik, pengembangan diri, dan referensi ilmiah.'],
                    ['q' => 'Bagaimana cara membeli buku di Mega Bookstore?', 'a' => 'Pilih buku yang Anda inginkan, masukkan ke keranjang atau klik "Beli via WhatsApp", lalu isi nama dan alamat singkat untuk terhubung langsung dengan admin kami.'],
                ];
            @endphp
            <div class="space-y-3" x-data="{ openIdx: 0 }">
                @foreach($faqs as $idx => $faq)
                    <div class="bg-white rounded-2xl border border-[#DDD8C8] overflow-hidden">
                        <button type="button" @click="openIdx = openIdx === {{ $idx }} ? null : {{ $idx }}"
                                class="w-full px-6 py-4 text-left font-bold text-sm sm:text-base text-[#171512] flex justify-between items-center cursor-pointer">
                            <span>{{ $faq['q'] }}</span>
                            <span class="text-[#DB8B00] text-xl" x-text="openIdx === {{ $idx }} ? '−' : '+'"></span>
                        </button>
                        <div x-show="openIdx === {{ $idx }}" x-cloak class="px-6 pb-5 text-sm text-[#6B6860] leading-relaxed">
                            {{ $faq['a'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- MANUSCRIPT SUBMISSION FORM (#ajukan-naskah) --}}
    <section id="ajukan-naskah" class="py-20 md:py-28 relative z-10 scroll-mt-24">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            <div class="bg-white rounded-3xl border border-[#DDD8C8] shadow-xl overflow-hidden">
                <div class="p-8 sm:p-12 border-b border-gray-100 text-center bg-[#EFEADD]/40">
                    <img src="https://ik.imagekit.io/yqhp1cmbp/logo_megapress.svg" alt="Mega Press Logo" class="w-12 h-12 mx-auto mb-4">
                    <h2 class="font-serif text-3xl sm:text-4xl text-[#171512] mb-2">
                        Mulai Langkah Pertama Bersama Mega Press
                    </h2>
                    <p class="text-sm text-[#6B6860] max-w-xl mx-auto">
                        Unggah naskah Anda (PDF/DOC/DOCX, maks 10 MB). Tim editor kami akan meninjau dan menghubungi Anda melalui email atau WhatsApp.
                    </p>
                </div>

                <form method="POST" action="{{ route('submissions.store') }}" enctype="multipart/form-data" class="p-8 sm:p-12 space-y-5">
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
                        <label for="synopsis" class="block text-xs font-bold uppercase tracking-wider text-[#171512] mb-1.5">
                            Sinopsis Singkat <span class="text-red-500">*</span>
                        </label>
                        <textarea id="synopsis" name="synopsis" rows="4" required
                                  placeholder="Jelaskan garis besar naskah Anda, target pembaca, dan keunikannya..."
                                  class="w-full rounded-xl border border-[#DDD8C8] bg-white px-4 py-3 text-sm text-[#171512] focus:outline-none focus:border-[#DB8B00]">{{ old('synopsis') }}</textarea>
                        @error('synopsis') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="manuscript_file" class="block text-xs font-bold uppercase tracking-wider text-[#171512] mb-1.5">
                            File Naskah (PDF, DOC, DOCX, maks 10 MB) <span class="text-red-500">*</span>
                        </label>
                        <input id="manuscript_file" type="file" name="manuscript_file" accept=".pdf,.doc,.docx" required
                               class="w-full rounded-xl border border-dashed border-[#DDD8C8] bg-[#FAF8F4] px-4 py-3 text-sm text-[#171512] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[#DB8B00] file:text-white hover:file:bg-[#b06f00]">
                        @error('manuscript_file') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-3 flex flex-col sm:flex-row gap-3 items-center justify-between">
                        <p class="text-xs text-gray-500">Naskah Anda dijamin kerahasiaannya oleh tim redaksi Mega Press.</p>
                        <x-button type="submit" variant="primary" size="lg" class="w-full sm:w-auto">
                            Kirim Naskah
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- NEWSLETTER --}}
    <section class="pb-20 relative z-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            <div class="bg-[#084c3c] text-white rounded-3xl p-8 sm:p-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h3 class="font-serif text-2xl sm:text-3xl mb-1">Dapatkan Info Terbitan &amp; Promo Terbaru</h3>
                    <p class="text-white/75 text-sm">Berlangganan newsletter resmi Mega Press tanpa spam.</p>
                </div>
                <form method="POST" action="{{ route('newsletter.subscribe') }}" class="flex w-full md:w-auto gap-2">
                    @csrf
                    <input type="email" name="email" required placeholder="Email Anda..."
                           class="flex-1 md:w-64 h-11 rounded-full px-4 text-sm bg-white text-[#171512] focus:outline-none">
                    <button type="submit" class="h-11 px-6 rounded-full bg-[#DB8B00] hover:bg-[#b06f00] text-white font-bold text-xs uppercase tracking-wider cursor-pointer shrink-0">
                        Langganan
                    </button>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
