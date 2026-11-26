@extends('layouts.app')

@section('title', ($queryText !== '' ? 'Hasil: ' . $queryText . ' | ' : '') . 'Pencarian | Workshop Indonesia Maju')

@section('content')
<div x-data="{ activeTab: 'all' }" class="bg-[#F3F4F7] min-h-screen pt-24 md:pt-36 pb-24 text-[#111218]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between border-t border-[#111218]/40 pt-3 pb-6 text-xs font-bold uppercase tracking-[0.18em]">
            <span>PENCARIAN // ARSIP WIM</span>
            <span class="text-[#2B3288]">Buku, Penulis &amp; Artikel</span>
        </div>

        <div class="mb-8">
            <h1 class="font-serif text-3xl sm:text-5xl text-[#111218] tracking-[-0.03em]">
                Cari Koleksi <em class="italic font-normal text-[#2B3288]">&amp;</em> Tulisan WIM
            </h1>
        </div>

        <div class="bg-white border border-[#C9CEDF] overflow-hidden">
            {{-- Search Input Form --}}
            <form method="GET" action="{{ route('search') }}" class="flex items-center border-b border-[#C9CEDF] px-4 sm:px-7 h-16 sm:h-20 gap-3">
                <svg class="w-5 h-5 text-[#2B3288] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="text" name="q" value="{{ $queryText }}" autofocus
                       placeholder="Cari judul buku, penulis, ISBN, kategori, atau artikel..."
                       class="flex-1 min-w-0 bg-transparent border-0 text-sm sm:text-lg text-[#111218] focus:outline-none">
                @if($queryText !== '')
                    <a href="{{ route('search') }}" class="text-xs font-bold uppercase tracking-wider text-[#636778] hover:text-[#2B3288] whitespace-nowrap px-2">
                        Reset
                    </a>
                @endif
                <button type="submit" class="px-5 sm:px-7 h-10 sm:h-11 bg-[#2B3288] hover:bg-[#1F256D] text-white text-xs font-bold uppercase tracking-[0.14em] cursor-pointer whitespace-nowrap transition-colors">
                    Cari
                </button>
            </form>

            <div class="p-5 sm:p-10 min-h-[420px]">
                @if($queryText === '')
                    <div class="space-y-10">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#2B3288] mb-4">Pencarian Populer</p>
                            <div class="flex flex-wrap gap-2.5">
                                @forelse($popularKeywords as $kw)
                                    <a href="{{ route('search', ['q' => $kw]) }}"
                                       class="px-4 py-2 border border-[#C9CEDF] bg-[#F3F4F7] hover:border-[#2B3288] hover:text-[#2B3288] text-xs font-bold uppercase tracking-wider text-[#111218] transition-colors">
                                        {{ $kw }}
                                    </a>
                                @empty
                                    @foreach($browseCategories as $cat)
                                        <a href="{{ route('search', ['q' => $cat->name]) }}"
                                           class="px-4 py-2 border border-[#C9CEDF] bg-[#F3F4F7] hover:border-[#2B3288] hover:text-[#2B3288] text-xs font-bold uppercase tracking-wider text-[#111218] transition-colors">
                                            {{ $cat->name }}
                                        </a>
                                    @endforeach
                                    @if($browseCategories->isEmpty())
                                        <a href="{{ route('bookstore.index') }}" class="text-sm font-bold text-[#2B3288] underline">
                                            Jelajahi semua koleksi di WIM Bookstore &rarr;
                                        </a>
                                    @endif
                                @endforelse
                            </div>
                        </div>

                        @if($recommendedBooks->isNotEmpty())
                            <div class="pt-6 border-t border-[#C9CEDF]">
                                <div class="flex items-center justify-between mb-5">
                                    <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#2B3288]">Rekomendasi Terbitan</p>
                                    <a href="{{ route('bookstore.index') }}" class="text-xs font-bold uppercase tracking-wider text-[#2B3288] hover:underline">
                                        Semua Buku &rarr;
                                    </a>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                                    @foreach($recommendedBooks as $recBook)
                                        <x-book-card :book="$recBook" />
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @elseif($books->isEmpty() && $authors->isEmpty() && $posts->isEmpty())
                    <div class="text-center py-20 space-y-4">
                        <h2 class="font-serif text-3xl text-[#111218]">Tidak ada hasil untuk "{{ $queryText }}"</h2>
                        <p class="text-sm text-[#636778]">Coba kata kunci lain atau jelajahi katalog lengkap WIM Bookstore.</p>
                        <div class="pt-2 flex flex-wrap justify-center gap-3">
                            <a href="{{ route('search') }}" class="px-5 py-2.5 border border-[#C9CEDF] text-xs font-bold uppercase tracking-wider text-[#111218] hover:border-[#2B3288]">
                                Reset Pencarian
                            </a>
                            <a href="{{ route('bookstore.index') }}" class="px-5 py-2.5 bg-[#2B3288] text-white text-xs font-bold uppercase tracking-wider hover:bg-[#1F256D]">
                                Buka Bookstore &rarr;
                            </a>
                        </div>
                    </div>
                @else
                    @php
                        $totalCount = $books->count() + $authors->count() + $posts->count();
                    @endphp
                    {{-- Tabs Filter Hasil --}}
                    <div class="flex flex-wrap items-center justify-between gap-4 pb-6 mb-8 border-b border-[#C9CEDF]">
                        <p class="text-xs text-[#636778]">
                            Menampilkan <strong class="text-[#111218]">{{ $totalCount }} hasil</strong> untuk "<strong class="text-[#2B3288]">{{ $queryText }}</strong>"
                        </p>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" @click="activeTab = 'all'"
                                    :class="activeTab === 'all' ? 'bg-[#2B3288] text-white border-[#2B3288]' : 'bg-[#F3F4F7] text-[#636778] border-[#C9CEDF] hover:text-[#2B3288]'"
                                    class="px-3.5 py-1.5 text-xs font-bold uppercase tracking-wider border cursor-pointer transition-colors">
                                Semua ({{ $totalCount }})
                            </button>
                            <button type="button" @click="activeTab = 'books'"
                                    :class="activeTab === 'books' ? 'bg-[#2B3288] text-white border-[#2B3288]' : 'bg-[#F3F4F7] text-[#636778] border-[#C9CEDF] hover:text-[#2B3288]'"
                                    class="px-3.5 py-1.5 text-xs font-bold uppercase tracking-wider border cursor-pointer transition-colors">
                                Buku ({{ $books->count() }})
                            </button>
                            <button type="button" @click="activeTab = 'authors'"
                                    :class="activeTab === 'authors' ? 'bg-[#2B3288] text-white border-[#2B3288]' : 'bg-[#F3F4F7] text-[#636778] border-[#C9CEDF] hover:text-[#2B3288]'"
                                    class="px-3.5 py-1.5 text-xs font-bold uppercase tracking-wider border cursor-pointer transition-colors">
                                Penulis ({{ $authors->count() }})
                            </button>
                            <button type="button" @click="activeTab = 'posts'"
                                    :class="activeTab === 'posts' ? 'bg-[#2B3288] text-white border-[#2B3288]' : 'bg-[#F3F4F7] text-[#636778] border-[#C9CEDF] hover:text-[#2B3288]'"
                                    class="px-3.5 py-1.5 text-xs font-bold uppercase tracking-wider border cursor-pointer transition-colors">
                                Artikel ({{ $posts->count() }})
                            </button>
                        </div>
                    </div>

                    <div class="space-y-10">
                        @if($books->isNotEmpty())
                            <div x-show="activeTab === 'all' || activeTab === 'books'">
                                <h3 class="text-xs font-bold uppercase tracking-[0.18em] text-[#2B3288] mb-4">Buku ({{ $books->count() }})</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach($books as $book)
                                        <a href="{{ route('bookstore.show', $book->slug) }}" class="flex gap-4 p-4 border border-[#C9CEDF] hover:border-[#2B3288] bg-[#F3F4F7]/50 hover:bg-white transition-colors">
                                            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-16 h-24 object-cover border border-[#C9CEDF] shrink-0 bg-white">
                                            <div class="min-w-0">
                                                <h4 class="font-serif text-lg text-[#111218] line-clamp-2">{{ $book->title }}</h4>
                                                <p class="text-xs text-[#636778] mt-0.5">{{ $book->author?->name ?? 'Workshop Indonesia Maju' }}</p>
                                                <p class="text-sm font-bold text-[#2B3288] mt-2">Rp {{ number_format((float)$book->final_price, 0, ',', '.') }}</p>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($authors->isNotEmpty())
                            <div x-show="activeTab === 'all' || activeTab === 'authors'">
                                <h3 class="text-xs font-bold uppercase tracking-[0.18em] text-[#2B3288] mb-4">Penulis ({{ $authors->count() }})</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($authors as $author)
                                        <a href="{{ route('bookstore.index', ['author' => $author->slug]) }}"
                                           class="p-4 border border-[#C9CEDF] hover:border-[#2B3288] flex items-center justify-between transition-colors">
                                            <span class="text-sm font-bold text-[#111218]">{{ $author->name }}</span>
                                            <span class="text-[11px] font-bold uppercase tracking-wider text-[#2B3288]">{{ $author->books_count }} buku &rarr;</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($posts->isNotEmpty())
                            <div x-show="activeTab === 'all' || activeTab === 'posts'">
                                <h3 class="text-xs font-bold uppercase tracking-[0.18em] text-[#2B3288] mb-4">Artikel Blog ({{ $posts->count() }})</h3>
                                <div class="divide-y divide-[#C9CEDF] border-t border-b border-[#C9CEDF]">
                                    @foreach($posts as $post)
                                        <a href="{{ route('blog.show', $post->slug) }}" class="block py-4 hover:text-[#2B3288] transition-colors">
                                            <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider text-[#2B3288] mb-1">
                                                <span>{{ $post->category?->name ?? 'Artikel' }}</span>
                                            </div>
                                            <h4 class="font-serif text-lg text-[#111218]">{{ $post->title }}</h4>
                                            <p class="text-xs text-[#636778] line-clamp-1 mt-1">{{ $post->excerpt }}</p>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
