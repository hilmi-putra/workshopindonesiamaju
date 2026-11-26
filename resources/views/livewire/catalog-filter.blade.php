<div>
    {{-- Architectural Filter & Search Bar --}}
    <div class="bg-white border border-[#C9CEDF] p-5 sm:p-6 mb-10 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-4 relative">
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Cari judul buku, penulis, atau ISBN..."
                       class="w-full h-11 border border-[#C9CEDF] bg-[#F3F4F7] px-4 text-sm text-[#111218] focus:outline-none focus:border-[#2B3288] focus:bg-white transition-colors">
            </div>

            <div class="md:col-span-2">
                <select wire:model.live="category" class="w-full h-11 border border-[#C9CEDF] bg-white px-3 text-sm text-[#111218] focus:outline-none focus:border-[#2B3288]">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <select wire:model.live="genre" class="w-full h-11 border border-[#C9CEDF] bg-white px-3 text-sm text-[#111218] focus:outline-none focus:border-[#2B3288]">
                    <option value="">Semua Genre</option>
                    @foreach($genres as $gen)
                        <option value="{{ $gen->slug }}">{{ $gen->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <select wire:model.live="author" class="w-full h-11 border border-[#C9CEDF] bg-white px-3 text-sm text-[#111218] focus:outline-none focus:border-[#2B3288]">
                    <option value="">Semua Penulis</option>
                    @foreach($authors as $auth)
                        <option value="{{ $auth->slug }}">{{ $auth->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <select wire:model.live="sort" class="w-full h-11 border border-[#C9CEDF] bg-white px-3 text-sm text-[#111218] focus:outline-none focus:border-[#2B3288]">
                    <option value="latest">Terbaru</option>
                    <option value="bestseller">Terlaris</option>
                    <option value="price_asc">Harga Terendah</option>
                    <option value="price_desc">Harga Tertinggi</option>
                    <option value="title_asc">Judul A–Z</option>
                </select>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 text-xs text-[#636778] pt-3 border-t border-[#E3E7F3]">
            <div class="flex flex-wrap items-center gap-3">
                <span class="font-bold uppercase tracking-wider text-[#111218]">Menampilkan {{ $books->total() }} buku</span>
                <select wire:model.live="publisher" class="border border-[#C9CEDF] bg-white px-2.5 py-1 text-xs focus:outline-none focus:border-[#2B3288]">
                    <option value="">Semua Penerbit</option>
                    @foreach($publishers as $pub)
                        <option value="{{ $pub->slug }}">{{ $pub->name }}</option>
                    @endforeach
                </select>
                @if($search || $category || $genre || $author || $publisher || $sort !== 'latest')
                    <button type="button" wire:click="resetFilters" class="text-[#2B3288] font-bold uppercase tracking-wider hover:underline cursor-pointer">
                        Reset Filter
                    </button>
                @endif
            </div>
            <div wire:loading class="text-[#2B3288] font-bold uppercase tracking-wider">Memuat katalog...</div>
        </div>
    </div>

    {{-- Grid Buku --}}
    @if($books->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($books as $book)
                <x-book-card :book="$book" wire:key="book-{{ $book->id }}" />
            @endforeach
        </div>

        <div class="mt-14">
            {{ $books->links() }}
        </div>
    @else
        <div class="bg-white border border-dashed border-[#C9CEDF] py-20 text-center">
            <p class="font-serif text-3xl text-[#111218] mb-2">Buku tidak ditemukan</p>
            <p class="text-sm text-[#636778] mb-6">Coba ubah kata kunci pencarian atau reset filter Anda.</p>
            <button type="button" wire:click="resetFilters" class="px-6 py-3 bg-[#2B3288] hover:bg-[#1F256D] text-white text-xs font-bold uppercase tracking-[0.15em] cursor-pointer transition-colors">
                Tampilkan Semua Buku
            </button>
        </div>
    @endif
</div>
