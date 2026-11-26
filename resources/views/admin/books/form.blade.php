@extends('layouts.admin')

@section('title', ($book->exists ? 'Edit Buku' : 'Tambah Buku') . ' | Admin WIM')
@section('breadcrumb', $book->exists ? 'Buku / Edit' : 'Buku / Tambah')
@section('page_title', $book->exists ? 'Edit Buku: ' . $book->title : 'Tambah Buku Baru')
@section('page_description', 'Lengkapi data buku secara bertahap mulai dari identitas, harga IDR & stok, cover, hingga publikasi.')

@php
    $rawPrice = old('price', $book->price);
    $initialPriceRaw = '';
    $initialPriceFormatted = '';
    if ($rawPrice !== null && $rawPrice !== '') {
        $numericPrice = (float) $rawPrice;
        $roundedPrice = (int) round($numericPrice);
        $initialPriceRaw = (string) $roundedPrice;
        $initialPriceFormatted = number_format($roundedPrice, 0, ',', '.');
    }

    $initialDiscount = (float) old('discount', $book->discount ?? 0);
    $initialStock = (string) old('stock', $book->stock ?? 0);
    $initialTitle = (string) old('title', $book->title ?? '');
    $initialStatus = (string) old('status', $book->status ?? 'published');

    $selectedCats = array_map('intval', old('categories', $book->categories->pluck('id')->all()));
    $selectedGenres = array_map('intval', old('genres', $book->genres->pluck('id')->all()));

    $initialCoverInput = (string) old('cover_image', $book->cover_image ?? '');
    $storedCoverValue = (string) ($book->cover_image ?? '');
    $storedCoverUrl = $book->cover_image ? $book->cover_url : '';

    $stepFieldMap = [
        1 => ['title', 'subtitle', 'isbn', 'author_id', 'publisher_id'],
        2 => ['price', 'discount', 'stock', 'page_count', 'weight', 'dimensions', 'cover_type'],
        3 => ['categories', 'genres', 'cover_image_file', 'cover_image'],
        4 => ['short_description', 'long_description', 'status', 'is_featured', 'is_bestseller'],
    ];

    $errorSteps = [];
    if ($errors->any()) {
        foreach ($errors->keys() as $errKey) {
            $baseKey = explode('.', $errKey)[0];
            foreach ($stepFieldMap as $sNum => $fields) {
                if (in_array($baseKey, $fields, true)) {
                    $errorSteps[] = $sNum;
                }
            }
        }
    }
    $errorSteps = array_values(array_unique($errorSteps));
    $initialStep = count($errorSteps) > 0 ? min($errorSteps) : 1;
@endphp

@section('content')
<div x-data="{
        step: {{ $initialStep }},
        maxStep: {{ $book->exists ? 4 : max(1, $initialStep) }},
        totalSteps: 4,
        isEdit: {{ $book->exists ? 'true' : 'false' }},
        errorSteps: @js($errorSteps),
        stepError: '',
        stepsMeta: [
            { num: 1, title: 'Identitas Buku', desc: 'Judul, ISBN, penulis & penerbit' },
            { num: 2, title: 'Harga & Stok', desc: 'Harga IDR, diskon & fisik' },
            { num: 3, title: 'Kategori & Cover', desc: 'Kategori, genre & preview cover' },
            { num: 4, title: 'Deskripsi & Terbit', desc: 'Sinopsis, status & publikasi' },
        ],

        // Ringkasan & harga IDR
        titleInput: @js($initialTitle),
        statusInput: @js($initialStatus),
        priceRaw: @js($initialPriceRaw),
        priceFormatted: @js($initialPriceFormatted),
        discountInput: {{ json_encode($initialDiscount) }},
        stockInput: @js($initialStock),
        selectedCategoriesCount: {{ count($selectedCats) }},
        selectedGenresCount: {{ count($selectedGenres) }},

        // Cover preview state
        filePreviewUrl: null,
        fileName: '',
        fileSizeText: '',
        dragging: false,
        urlInput: @js($initialCoverInput),
        storedCoverValue: @js($storedCoverValue),
        storedCoverUrl: @js($storedCoverUrl),
        imageLoadError: false,

        formatIdr(num) {
            const val = Number(num);
            if (!Number.isFinite(val)) return 'Rp 0';
            return 'Rp ' + Math.round(val).toLocaleString('id-ID');
        },
        onPriceInput(value) {
            const digits = String(value || '').replace(/\D/g, '');
            this.priceRaw = digits;
            this.priceFormatted = digits ? Number(digits).toLocaleString('id-ID') : '';
            this.stepError = '';
        },
        get numericPrice() {
            return this.priceRaw === '' ? 0 : Number(this.priceRaw);
        },
        get numericDiscount() {
            const d = Number(this.discountInput);
            if (!Number.isFinite(d) || d < 0) return 0;
            return Math.min(100, d);
        },
        get finalPrice() {
            const p = this.numericPrice;
            const d = this.numericDiscount;
            if (d <= 0) return p;
            return Math.max(0, Math.round(p - (p * (d / 100))));
        },
        get savingsAmount() {
            return Math.max(0, this.numericPrice - this.finalPrice);
        },

        // Cover preview helpers
        get previewSrc() {
            if (this.filePreviewUrl) return this.filePreviewUrl;
            const raw = (this.urlInput || '').trim();
            if (!raw) return '';
            if (this.storedCoverUrl && raw === this.storedCoverValue) {
                return this.storedCoverUrl;
            }
            if (/^(https?:\/\/|data:|blob:|\/)/i.test(raw)) {
                return raw;
            }
            return '/storage/' + raw.replace(/^\/+/, '').replace(/^storage\//, '');
        },
        get previewBadge() {
            if (this.filePreviewUrl) return 'File Upload Baru';
            const raw = (this.urlInput || '').trim();
            if (!raw) return '';
            if (this.storedCoverUrl && raw === this.storedCoverValue) return 'Cover Tersimpan';
            return 'Preview URL Cover';
        },
        formatBytes(bytes) {
            if (!bytes) return '';
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
        },
        applyCoverFile(file) {
            if (!file) return;
            if (!file.type.startsWith('image/')) {
                this.stepError = 'File cover harus berupa gambar (JPG, PNG, WEBP, atau GIF).';
                return;
            }
            if (this.filePreviewUrl) {
                URL.revokeObjectURL(this.filePreviewUrl);
            }
            this.filePreviewUrl = URL.createObjectURL(file);
            this.fileName = file.name;
            this.fileSizeText = this.formatBytes(file.size);
            this.imageLoadError = false;
            this.stepError = '';
        },
        handleCoverFileChange(event) {
            const file = event.target.files?.[0];
            if (file) this.applyCoverFile(file);
        },
        handleCoverDrop(event) {
            this.dragging = false;
            const file = event.dataTransfer?.files?.[0];
            if (!file) return;
            if (this.$refs.coverFileInput) {
                const dt = new DataTransfer();
                dt.items.add(file);
                this.$refs.coverFileInput.files = dt.files;
            }
            this.applyCoverFile(file);
        },
        clearCoverFile() {
            if (this.filePreviewUrl) {
                URL.revokeObjectURL(this.filePreviewUrl);
            }
            this.filePreviewUrl = null;
            this.fileName = '';
            this.fileSizeText = '';
            this.imageLoadError = false;
            if (this.$refs.coverFileInput) {
                this.$refs.coverFileInput.value = '';
            }
        },

        // Multi-step validation & navigation
        validateStep(targetStep) {
            this.stepError = '';
            if (targetStep === 1) {
                if (!this.titleInput.trim()) {
                    this.stepError = 'Judul buku wajib diisi terlebih dahulu.';
                    return { ok: false, ref: 'titleInput' };
                }
                const authorEl = this.$refs.authorSelect;
                if (authorEl && !authorEl.value) {
                    this.stepError = 'Silakan pilih penulis buku.';
                    return { ok: false, ref: 'authorSelect' };
                }
                const pubEl = this.$refs.publisherSelect;
                if (pubEl && !pubEl.value) {
                    this.stepError = 'Silakan pilih penerbit buku.';
                    return { ok: false, ref: 'publisherSelect' };
                }
            }
            if (targetStep === 2) {
                if (this.priceRaw === '' || Number(this.priceRaw) < 0) {
                    this.stepError = 'Harga buku (IDR) wajib diisi dengan nominal valid.';
                    return { ok: false, ref: 'priceDisplay' };
                }
                if (this.numericDiscount < 0 || this.numericDiscount > 100) {
                    this.stepError = 'Diskon harus bernilai antara 0% sampai 100%.';
                    return { ok: false, ref: 'discountInputEl' };
                }
                if (this.stockInput === '' || Number(this.stockInput) < 0 || !Number.isInteger(Number(this.stockInput))) {
                    this.stepError = 'Stok awal wajib berupa bilangan bulat minimal 0.';
                    return { ok: false, ref: 'stockInputEl' };
                }
            }
            return { ok: true, ref: null };
        },
        focusField(refName) {
            if (!refName) return;
            this.$nextTick(() => {
                const el = this.$refs[refName];
                if (el && typeof el.focus === 'function') {
                    el.focus();
                }
            });
        },
        goToStep(target) {
            if (target === this.step) return;
            if (target < this.step || this.isEdit || target <= this.maxStep) {
                this.stepError = '';
                this.step = target;
                return;
            }
            for (let s = this.step; s < target; s++) {
                const check = this.validateStep(s);
                if (!check.ok) {
                    this.step = s;
                    this.focusField(check.ref);
                    return;
                }
            }
            this.step = target;
            if (target > this.maxStep) this.maxStep = target;
        },
        nextStep() {
            const check = this.validateStep(this.step);
            if (!check.ok) {
                this.focusField(check.ref);
                return;
            }
            if (this.step < this.totalSteps) {
                this.step++;
                if (this.step > this.maxStep) this.maxStep = this.step;
            }
        },
        prevStep() {
            this.stepError = '';
            if (this.step > 1) this.step--;
        },
        handleFormSubmit(event) {
            for (let s = 1; s <= this.totalSteps; s++) {
                const check = this.validateStep(s);
                if (!check.ok) {
                    event.preventDefault();
                    this.step = s;
                    this.focusField(check.ref);
                    return;
                }
            }
        }
    }"
     class="max-w-5xl space-y-6">

    {{-- Stepper Navigation --}}
    <div class="rounded-2xl border border-[#E3E7F3] bg-[#F5F7FC] p-4 sm:p-5">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            <template x-for="item in stepsMeta" :key="item.num">
                <button type="button"
                        @click="goToStep(item.num)"
                        :class="{
                            'bg-[#2B3288] text-white border-[#2B3288] shadow-sm shadow-[#2B3288]/20': step === item.num,
                            'bg-white text-[#2B3288] border-[#2B3288]/25 hover:border-[#2B3288]/50': step !== item.num && (item.num < step || isEdit || item.num <= maxStep),
                            'bg-white/70 text-gray-500 border-[#E3E7F3] hover:bg-white': step !== item.num && !isEdit && item.num > maxStep
                        }"
                        class="relative flex items-center gap-3 p-3 rounded-xl border text-left transition-all cursor-pointer">
                    <span :class="{
                              'bg-white text-[#2B3288]': step === item.num,
                              'bg-[#2B3288]/10 text-[#2B3288]': step !== item.num && (item.num < step || isEdit || item.num <= maxStep),
                              'bg-gray-100 text-gray-400': step !== item.num && !isEdit && item.num > maxStep
                          }"
                          class="size-8 rounded-lg flex items-center justify-center text-xs font-extrabold shrink-0">
                        <template x-if="item.num < step">
                            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                            </svg>
                        </template>
                        <template x-if="item.num >= step">
                            <span x-text="item.num"></span>
                        </template>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold leading-tight truncate" x-text="item.title"></p>
                        <p :class="step === item.num ? 'text-white/80' : 'text-gray-400'"
                           class="text-[11px] truncate mt-0.5 hidden sm:block" x-text="item.desc"></p>
                    </div>
                    <span x-show="errorSteps.includes(item.num)" x-cloak
                          title="Ada error validasi pada langkah ini"
                          class="absolute -top-1.5 -right-1.5 size-3.5 rounded-full bg-red-600 ring-2 ring-white"></span>
                </button>
            </template>
        </div>

        <div class="mt-4 h-1.5 w-full rounded-full bg-[#E3E7F3] overflow-hidden">
            <div class="h-full rounded-full bg-[#2B3288] transition-all duration-300"
                 :style="'width: ' + ((step / totalSteps) * 100) + '%'"></div>
        </div>
    </div>

    {{-- Step Validation Inline Alert --}}
    <div x-show="stepError" x-cloak x-transition
         class="flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        <svg class="size-5 shrink-0 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
        </svg>
        <p class="flex-1 font-medium" x-text="stepError"></p>
        <button type="button" @click="stepError = ''" class="text-xs font-bold uppercase text-red-600 hover:underline cursor-pointer">Tutup</button>
    </div>

    <form method="POST"
          action="{{ $book->exists ? route('admin.books.update', $book) : route('admin.books.store') }}"
          enctype="multipart/form-data"
          novalidate
          @submit="handleFormSubmit($event)"
          class="space-y-6">
        @csrf
        @if($book->exists)
            @method('PUT')
        @endif
        <input type="hidden" name="book_type" value="{{ old('book_type', $book->book_type ?? 'physical') }}">

        {{-- STEP 1: IDENTITAS BUKU --}}
        <section x-show="step === 1" x-transition.opacity class="rounded-2xl border border-[#E3E7F3] bg-white p-5 sm:p-6 space-y-5">
            <div class="pb-3 border-b border-[#E3E7F3]">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#2B3288]">Langkah 1 dari 4</span>
                <h2 class="text-lg font-bold text-[#171512]">Identitas Buku</h2>
                <p class="text-xs text-gray-500 mt-0.5">Informasi utama judul, ISBN, penulis, dan penerbit buku.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="w-full">
                    <label for="title" class="block text-xs font-bold uppercase tracking-wider text-[#171512] mb-1.5">
                        Judul Buku <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="title"
                           id="title"
                           x-ref="titleInput"
                           x-model="titleInput"
                           @input="stepError = ''"
                           placeholder="Masukkan judul lengkap buku"
                           required
                           class="w-full rounded-xl border border-[#D9DFEE] bg-white px-4 py-2.5 text-sm text-[#171512] focus:outline-none focus:border-[#2B3288] focus:ring-2 focus:ring-[#2B3288]/15 transition-colors placeholder:text-gray-400">
                    @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <x-input name="subtitle" label="Subjudul" :value="$book->subtitle" placeholder="Opsional" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <x-input name="isbn" label="ISBN" :value="$book->isbn" placeholder="Contoh: 978-602-..." />

                <div>
                    <label for="author_id" class="block text-xs font-bold uppercase tracking-wider text-[#171512] mb-1.5">
                        Penulis <span class="text-red-500">*</span>
                    </label>
                    <select id="author_id" name="author_id" x-ref="authorSelect" @change="stepError = ''" required
                            class="w-full h-11 rounded-xl border border-[#D9DFEE] px-3 text-sm bg-white focus:outline-none focus:border-[#2B3288] focus:ring-2 focus:ring-[#2B3288]/15">
                        <option value="" disabled @selected(!old('author_id', $book->author_id))>-- Pilih Penulis --</option>
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}" @selected(old('author_id', $book->author_id) == $author->id)>{{ $author->name }}</option>
                        @endforeach
                    </select>
                    @error('author_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="publisher_id" class="block text-xs font-bold uppercase tracking-wider text-[#171512] mb-1.5">
                        Penerbit <span class="text-red-500">*</span>
                    </label>
                    <select id="publisher_id" name="publisher_id" x-ref="publisherSelect" @change="stepError = ''" required
                            class="w-full h-11 rounded-xl border border-[#D9DFEE] px-3 text-sm bg-white focus:outline-none focus:border-[#2B3288] focus:ring-2 focus:ring-[#2B3288]/15">
                        <option value="" disabled @selected(!old('publisher_id', $book->publisher_id))>-- Pilih Penerbit --</option>
                        @foreach($publishers as $pub)
                            <option value="{{ $pub->id }}" @selected(old('publisher_id', $book->publisher_id) == $pub->id)>{{ $pub->name }}</option>
                        @endforeach
                    </select>
                    @error('publisher_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </section>

        {{-- STEP 2: HARGA IDR, STOK & SPESIFIKASI --}}
        <section x-show="step === 2" x-cloak x-transition.opacity class="rounded-2xl border border-[#E3E7F3] bg-white p-5 sm:p-6 space-y-6">
            <div class="pb-3 border-b border-[#E3E7F3]">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#2B3288]">Langkah 2 dari 4</span>
                <h2 class="text-lg font-bold text-[#171512]">Harga (IDR), Stok &amp; Spesifikasi</h2>
                <p class="text-xs text-gray-500 mt-0.5">Atur harga jual dalam Rupiah dengan format pemisah ribuan otomatis, diskon, stok, dan detail fisik buku.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                <div>
                    <label for="price_display" class="block text-xs font-bold uppercase tracking-wider text-[#171512] mb-1.5">
                        Harga Normal (IDR) <span class="text-red-500">*</span>
                    </label>
                    <div class="flex rounded-xl border border-[#D9DFEE] bg-white overflow-hidden focus-within:border-[#2B3288] focus-within:ring-2 focus-within:ring-[#2B3288]/15 transition-colors">
                        <span class="inline-flex items-center px-3.5 bg-[#F5F7FC] border-r border-[#D9DFEE] text-xs font-extrabold text-[#2B3288] select-none">
                            Rp
                        </span>
                        <input type="text"
                               id="price_display"
                               x-ref="priceDisplay"
                               inputmode="numeric"
                               autocomplete="off"
                               :value="priceFormatted"
                               @input="onPriceInput($event.target.value)"
                               placeholder="95.000"
                               class="w-full h-11 px-3.5 text-sm font-semibold text-[#171512] bg-white border-0 focus:outline-none focus:ring-0 placeholder:text-gray-400">
                    </div>
                    <input type="hidden" name="price" :value="priceRaw">
                    <p class="mt-1 text-[11px] text-gray-400">Ketik angka saja (contoh: 95000 &rarr; Rp 95.000).</p>
                    @error('price')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="discount" class="block text-xs font-bold uppercase tracking-wider text-[#171512] mb-1.5">
                        Diskon (%)
                    </label>
                    <div class="flex rounded-xl border border-[#D9DFEE] bg-white overflow-hidden focus-within:border-[#2B3288] focus-within:ring-2 focus-within:ring-[#2B3288]/15 transition-colors">
                        <input type="number"
                               step="0.01"
                               min="0"
                               max="100"
                               name="discount"
                               id="discount"
                               x-ref="discountInputEl"
                               x-model="discountInput"
                               @input="stepError = ''"
                               class="w-full h-11 px-3.5 text-sm font-semibold text-[#171512] bg-white border-0 focus:outline-none focus:ring-0">
                        <span class="inline-flex items-center px-3.5 bg-[#F5F7FC] border-l border-[#D9DFEE] text-xs font-extrabold text-gray-500 select-none">
                            %
                        </span>
                    </div>
                    @error('discount')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="stock" class="block text-xs font-bold uppercase tracking-wider text-[#171512] mb-1.5">
                        Stok {{ $book->exists ? 'Saat Ini' : 'Awal' }} <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           min="0"
                           step="1"
                           name="stock"
                           id="stock"
                           x-ref="stockInputEl"
                           x-model="stockInput"
                           @input="stepError = ''"
                           required
                           class="w-full h-11 rounded-xl border border-[#D9DFEE] bg-white px-4 text-sm font-semibold text-[#171512] focus:outline-none focus:border-[#2B3288] focus:ring-2 focus:ring-[#2B3288]/15 transition-colors">
                    @error('stock')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Preview Perhitungan Harga IDR --}}
            <div class="rounded-2xl border border-[#D9DFEE] bg-[#F5F7FC] p-4 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-[#2B3288]/70">Preview Harga Tampil Pembeli (IDR)</p>
                    <div class="flex flex-wrap items-baseline gap-2.5 mt-1">
                        <span class="text-2xl font-extrabold text-[#2B3288]" x-text="formatIdr(finalPrice)"></span>
                        <template x-if="numericDiscount > 0 && numericPrice > 0">
                            <span class="flex items-center gap-2 text-xs">
                                <span class="line-through text-gray-400" x-text="formatIdr(numericPrice)"></span>
                                <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 font-bold"
                                      x-text="'Hemat ' + formatIdr(savingsAmount)"></span>
                            </span>
                        </template>
                    </div>
                </div>
                <span class="px-3 py-1 rounded-full bg-white border border-[#D9DFEE] text-xs font-semibold text-gray-600">
                    Mata Uang: <strong class="text-[#2B3288]">IDR (Rupiah)</strong>
                </span>
            </div>

            {{-- Spesifikasi Fisik --}}
            <div class="pt-2 border-t border-[#E3E7F3]">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-3">Spesifikasi Fisik Buku</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <x-input type="number" min="1" name="page_count" label="Jml Halaman" :value="$book->page_count" placeholder="Contoh: 240" />
                    <x-input type="number" step="0.01" min="0" name="weight" label="Berat (gram)" :value="$book->weight" placeholder="Contoh: 320" />
                    <x-input name="dimensions" label="Dimensi (cm)" :value="$book->dimensions" placeholder="14 x 20 cm" />
                    <div>
                        <label for="cover_type" class="block text-xs font-bold uppercase tracking-wider text-[#171512] mb-1.5">Cover Type</label>
                        <select id="cover_type" name="cover_type" class="w-full h-11 rounded-xl border border-[#D9DFEE] px-3 text-sm bg-white focus:outline-none focus:border-[#2B3288] focus:ring-2 focus:ring-[#2B3288]/15">
                            <option value="softcover" @selected(old('cover_type', $book->cover_type) === 'softcover')>Softcover</option>
                            <option value="hardcover" @selected(old('cover_type', $book->cover_type) === 'hardcover')>Hardcover</option>
                            <option value="other" @selected(old('cover_type', $book->cover_type) === 'other')>Lainnya</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        {{-- STEP 3: KATEGORI, GENRE & COVER PREVIEW --}}
        <section x-show="step === 3" x-cloak x-transition.opacity class="rounded-2xl border border-[#E3E7F3] bg-white p-5 sm:p-6 space-y-6">
            <div class="pb-3 border-b border-[#E3E7F3]">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#2B3288]">Langkah 3 dari 4</span>
                <h2 class="text-lg font-bold text-[#171512]">Kategori, Genre &amp; Upload Cover</h2>
                <p class="text-xs text-gray-500 mt-0.5">Kelompokkan kategori/genre buku serta upload gambar cover dengan preview langsung.</p>
            </div>

            {{-- Upload Cover + Preview Card --}}
            <div class="rounded-2xl border border-[#E3E7F3] bg-[#F5F7FC]/70 p-4 sm:p-5">
                <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-6 items-start">
                    {{-- Live Cover Preview --}}
                    <div class="flex flex-col items-center">
                        <div class="relative w-44 aspect-[2/3] rounded-2xl border-2 border-[#D9DFEE] bg-white overflow-hidden shadow-sm flex items-center justify-center">
                            <template x-if="previewSrc && !imageLoadError">
                                <img :src="previewSrc"
                                     alt="Preview Cover Buku"
                                     x-on:error="imageLoadError = true"
                                     x-on:load="imageLoadError = false"
                                     class="w-full h-full object-cover">
                            </template>

                            <template x-if="!previewSrc || imageLoadError">
                                <div class="p-4 text-center space-y-2">
                                    <div class="size-12 mx-auto rounded-2xl bg-[#F5F7FC] border border-[#E3E7F3] flex items-center justify-center text-[#2B3288]/50">
                                        <svg class="size-6" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs font-bold text-[#171512]"
                                       x-text="imageLoadError ? 'Gagal Memuat Gambar' : 'Belum Ada Cover'"></p>
                                    <p class="text-[11px] text-gray-400 leading-snug"
                                       x-text="imageLoadError ? 'Periksa kembali URL atau pilih file gambar lain.' : 'Rasio rekomendasi 2:3 (contoh 600x900 px).'"></p>
                                </div>
                            </template>

                            <span x-show="previewBadge && !imageLoadError" x-cloak
                                  class="absolute top-2.5 left-2.5 px-2.5 py-1 rounded-full bg-[#2B3288]/90 text-white text-[10px] font-bold uppercase tracking-wider shadow-xs"
                                  x-text="previewBadge"></span>
                        </div>
                        <p class="mt-2 text-[11px] text-gray-500 text-center font-medium">Preview Cover Buku</p>
                    </div>

                    {{-- Dropzone & URL Cover Input --}}
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-[#171512] mb-2">
                                Upload Cover Baru
                            </label>

                            <div @dragover.prevent="dragging = true"
                                 @dragleave.prevent="dragging = false"
                                 @drop.prevent="handleCoverDrop($event)"
                                 @click="$refs.coverFileInput.click()"
                                 :class="dragging ? 'border-[#2B3288] bg-[#2B3288]/5 ring-2 ring-[#2B3288]/15' : 'border-[#D9DFEE] bg-white hover:border-[#2B3288]/60 hover:bg-[#2B3288]/[0.02]'"
                                 class="rounded-2xl border-2 border-dashed p-5 text-center transition-all cursor-pointer">
                                <input x-ref="coverFileInput"
                                       type="file"
                                       name="cover_image_file"
                                       accept="image/*"
                                       @change="handleCoverFileChange($event)"
                                       class="sr-only">

                                <div class="size-11 mx-auto rounded-xl bg-[#2B3288]/10 text-[#2B3288] flex items-center justify-center mb-2.5">
                                    <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-[#171512]">Klik untuk pilih gambar cover atau tarik file ke sini</p>
                                <p class="text-xs text-gray-500 mt-1">Format JPG, PNG, WEBP &bull; Maksimal 4 MB</p>
                            </div>

                            <div x-show="fileName" x-cloak
                                 class="mt-2.5 flex items-center justify-between gap-3 rounded-xl border border-[#2B3288]/20 bg-white px-3.5 py-2.5 text-xs">
                                <div class="min-w-0">
                                    <p class="font-bold text-[#2B3288] truncate" x-text="fileName"></p>
                                    <p class="text-gray-400" x-text="fileSizeText"></p>
                                </div>
                                <button type="button"
                                        @click="clearCoverFile()"
                                        class="px-2.5 py-1 rounded-lg bg-red-50 text-red-600 font-bold hover:bg-red-100 transition-colors cursor-pointer shrink-0">
                                    Hapus Pilihan
                                </button>
                            </div>

                            @error('cover_image_file')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="cover_image" class="block text-xs font-bold uppercase tracking-wider text-[#171512] mb-1.5">
                                Atau URL Cover
                            </label>
                            <input type="text"
                                   name="cover_image"
                                   id="cover_image"
                                   x-model="urlInput"
                                   @input="imageLoadError = false"
                                   placeholder="https://... atau path storage/books/..."
                                   class="w-full rounded-xl border border-[#D9DFEE] bg-white px-4 py-2.5 text-sm text-[#171512] focus:outline-none focus:border-[#2B3288] focus:ring-2 focus:ring-[#2B3288]/15 transition-colors placeholder:text-gray-400">
                            <p class="mt-1 text-[11px] text-gray-400">Jika file upload dipilih, file upload baru akan menggantikan URL cover saat disimpan.</p>
                            @error('cover_image')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kategori & Genre --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-xs font-bold uppercase tracking-wider text-[#171512]">Kategori</label>
                        <span class="px-2 py-0.5 rounded-full bg-[#2B3288]/10 text-[#2B3288] text-[11px] font-bold"
                              x-text="selectedCategoriesCount + ' dipilih'"></span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 p-3 rounded-xl border border-[#D9DFEE] bg-[#F5F7FC]/40 max-h-48 overflow-y-auto">
                        @foreach($categories as $cat)
                            <label class="flex items-center gap-2 text-xs p-1.5 rounded-lg hover:bg-white cursor-pointer transition-colors">
                                <input type="checkbox"
                                       name="categories[]"
                                       value="{{ $cat->id }}"
                                       @checked(in_array($cat->id, $selectedCats))
                                       @change="selectedCategoriesCount = $el.closest('.grid').querySelectorAll('input:checked').length">
                                <span class="truncate">{{ $cat->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-xs font-bold uppercase tracking-wider text-[#171512]">Genre</label>
                        <span class="px-2 py-0.5 rounded-full bg-[#2B3288]/10 text-[#2B3288] text-[11px] font-bold"
                              x-text="selectedGenresCount + ' dipilih'"></span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 p-3 rounded-xl border border-[#D9DFEE] bg-[#F5F7FC]/40 max-h-48 overflow-y-auto">
                        @foreach($genres as $gen)
                            <label class="flex items-center gap-2 text-xs p-1.5 rounded-lg hover:bg-white cursor-pointer transition-colors">
                                <input type="checkbox"
                                       name="genres[]"
                                       value="{{ $gen->id }}"
                                       @checked(in_array($gen->id, $selectedGenres))
                                       @change="selectedGenresCount = $el.closest('.grid').querySelectorAll('input:checked').length">
                                <span class="truncate">{{ $gen->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- STEP 4: DESKRIPSI, STATUS & PUBLIKASI --}}
        <section x-show="step === 4" x-cloak x-transition.opacity class="rounded-2xl border border-[#E3E7F3] bg-white p-5 sm:p-6 space-y-5">
            <div class="pb-3 border-b border-[#E3E7F3]">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#2B3288]">Langkah 4 dari 4</span>
                <h2 class="text-lg font-bold text-[#171512]">Deskripsi &amp; Publikasi</h2>
                <p class="text-xs text-gray-500 mt-0.5">Tambahkan ringkasan, sinopsis lengkap, status tampil, dan tinjau ringkasan sebelum menyimpan.</p>
            </div>

            <div>
                <label for="short_description" class="block text-xs font-bold uppercase tracking-wider text-[#171512] mb-1.5">Deskripsi Singkat</label>
                <textarea id="short_description" name="short_description" rows="2"
                          placeholder="Ringkasan singkat yang tampil di kartu katalog atau cuplikan buku..."
                          class="w-full rounded-xl border border-[#D9DFEE] px-4 py-2.5 text-sm focus:outline-none focus:border-[#2B3288] focus:ring-2 focus:ring-[#2B3288]/15">{{ old('short_description', $book->short_description) }}</textarea>
            </div>

            <div>
                <label for="long_description" class="block text-xs font-bold uppercase tracking-wider text-[#171512] mb-1.5">Sinopsis / Deskripsi Lengkap</label>
                <textarea id="long_description" name="long_description" rows="5"
                          placeholder="Tulis sinopsis atau ulasan lengkap buku..."
                          class="w-full rounded-xl border border-[#D9DFEE] px-4 py-2.5 text-sm focus:outline-none focus:border-[#2B3288] focus:ring-2 focus:ring-[#2B3288]/15">{{ old('long_description', $book->long_description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label for="status" class="block text-xs font-bold uppercase tracking-wider text-[#171512] mb-1.5">
                        Status Publikasi <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status" x-model="statusInput"
                            class="w-full h-11 rounded-xl border border-[#D9DFEE] px-3 text-sm bg-white focus:outline-none focus:border-[#2B3288] focus:ring-2 focus:ring-[#2B3288]/15">
                        <option value="published" @selected(old('status', $book->status) === 'published')>Published (Tampil di Toko)</option>
                        <option value="draft" @selected(old('status', $book->status) === 'draft')>Draft (Disimpan Internal)</option>
                        <option value="archived" @selected(old('status', $book->status) === 'archived')>Archived (Diarsipkan)</option>
                    </select>
                    @error('status')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <label class="flex items-center gap-3 h-11 px-4 rounded-xl border border-[#D9DFEE] bg-[#F5F7FC]/60 text-sm font-semibold text-[#171512] cursor-pointer hover:border-[#2B3288]/40">
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $book->is_featured))>
                    <span>Buku Pilihan (Featured)</span>
                </label>

                <label class="flex items-center gap-3 h-11 px-4 rounded-xl border border-[#D9DFEE] bg-[#F5F7FC]/60 text-sm font-semibold text-[#171512] cursor-pointer hover:border-[#2B3288]/40">
                    <input type="checkbox" name="is_bestseller" value="1" @checked(old('is_bestseller', $book->is_bestseller))>
                    <span>Bestseller</span>
                </label>
            </div>

            {{-- Ringkasan Buku Sebelum Simpan --}}
            <div class="rounded-2xl border border-[#D9DFEE] bg-[#F5F7FC] p-4 flex items-center gap-4">
                <div class="w-12 h-16 rounded-lg bg-white border border-[#D9DFEE] overflow-hidden shrink-0 flex items-center justify-center">
                    <template x-if="previewSrc && !imageLoadError">
                        <img :src="previewSrc" alt="Mini preview cover" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!previewSrc || imageLoadError">
                        <svg class="size-5 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
                        </svg>
                    </template>
                </div>
                <div class="min-w-0 flex-1 grid grid-cols-1 sm:grid-cols-4 gap-2 text-xs">
                    <div class="sm:col-span-2 min-w-0">
                        <p class="text-[11px] uppercase font-bold text-gray-400">Judul Buku</p>
                        <p class="font-bold text-[#171512] truncate" x-text="titleInput.trim() || '-'"></p>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase font-bold text-gray-400">Harga Akhir</p>
                        <p class="font-extrabold text-[#2B3288]" x-text="formatIdr(finalPrice)"></p>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase font-bold text-gray-400">Stok &amp; Taksonomi</p>
                        <p class="font-semibold text-[#171512]"
                           x-text="(stockInput || 0) + ' stok • ' + selectedCategoriesCount + ' kat • ' + selectedGenresCount + ' gen'"></p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Wizard Navigation Bar --}}
        <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
            <div class="flex items-center gap-2.5">
                <a href="{{ route('admin.books.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-[#D9DFEE] text-sm font-semibold text-gray-600 hover:bg-[#F5F7FC] transition-colors">
                    Batal
                </a>
                <button type="button"
                        x-show="step > 1"
                        x-cloak
                        @click="prevStep()"
                        class="px-5 py-2.5 rounded-xl border border-[#D9DFEE] bg-white text-sm font-semibold text-[#2B3288] hover:bg-[#F5F7FC] transition-colors cursor-pointer">
                    &larr; Sebelumnya
                </button>
            </div>

            <div class="flex items-center gap-3">
                <span class="hidden sm:inline text-xs font-semibold text-gray-400"
                      x-text="'Langkah ' + step + ' / ' + totalSteps"></span>

                @if($book->exists)
                    <button type="submit"
                            x-show="step < totalSteps"
                            x-cloak
                            class="px-5 py-2.5 rounded-xl border border-[#2B3288]/30 bg-white text-sm font-bold text-[#2B3288] hover:bg-[#F5F7FC] transition-colors cursor-pointer">
                        Simpan Langsung
                    </button>
                @endif

                <button type="button"
                        x-show="step < totalSteps"
                        @click="nextStep()"
                        class="inline-flex items-center gap-2 h-11 px-6 rounded-xl bg-[#2B3288] hover:bg-[#20266E] text-white text-sm font-semibold shadow-sm transition-colors cursor-pointer">
                    <span>Lanjut</span>
                    <span aria-hidden="true">&rarr;</span>
                </button>

                <x-button x-show="step === totalSteps" x-cloak type="submit" variant="primary">
                    {{ $book->exists ? 'Simpan Perubahan' : 'Tambah Buku' }}
                </x-button>
            </div>
        </div>
    </form>
</div>
@endsection
