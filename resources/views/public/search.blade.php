@extends('layouts.app')

@section('title', 'Pencarian | Mega Press')

@section('content')
<div class="bg-[#FAF8F4] min-h-screen pt-32 pb-24">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <div class="bg-white rounded-3xl shadow-xs border border-[#DDD8C8] overflow-hidden">
            <form method="GET" action="{{ route('search') }}" class="flex items-center border-b border-gray-100 px-6 sm:px-8 h-20">
                <svg class="w-6 h-6 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="text" name="q" value="{{ $queryText }}" autofocus
                       placeholder="Cari buku, penulis, ISBN, atau artikel..."
                       class="flex-1 bg-transparent border-0 px-4 text-lg sm:text-xl text-[#171512] focus:outline-none">
                <button type="submit" class="px-5 py-2 rounded-full bg-[#DB8B00] text-white text-xs font-bold uppercase tracking-wider cursor-pointer">
                    Cari
                </button>
            </form>

            <div class="p-6 sm:p-10 min-h-[420px]">
                @if($queryText === '')
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-[#DB8B00] mb-4">Pencarian Populer</p>
                        <div class="flex flex-wrap gap-3">
                            @forelse($popularKeywords as $kw)
                                <a href="{{ route('search', ['q' => $kw]) }}" class="px-5 py-2.5 rounded-full border border-[#DDD8C8] hover:border-[#DB8B00] text-sm font-medium text-[#171512]">
                                    {{ $kw }}
                                </a>
                            @empty
                                <a href="{{ route('bookstore.index') }}" class="text-sm text-[#DB8B00] underline">Jelajahi semua koleksi di Mega Bookstore</a>
                            @endforelse
                        </div>
                    </div>
                @elseif($books->isEmpty() && $authors->isEmpty() && $posts->isEmpty())
                    <div class="text-center py-20">
                        <h2 class="font-serif text-2xl text-[#171512] mb-2">Tidak ada hasil untuk "{{ $queryText }}"</h2>
                        <p class="text-sm text-gray-500">Coba kata kunci lain atau lihat katalog lengkap di Mega Bookstore.</p>
                    </div>
                @else
                    <div class="space-y-10">
                        @if($books->isNotEmpty())
                            <div>
                                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-4">Buku ({{ $books->count() }})</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach($books as $book)
                                        <a href="{{ route('bookstore.show', $book->slug) }}" class="flex gap-4 p-4 rounded-2xl border border-[#DDD8C8] hover:border-[#DB8B00] transition-colors">
                                            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-16 h-24 object-cover rounded-md shrink-0">
                                            <div>
                                                <h4 class="font-serif text-lg text-[#171512] line-clamp-2">{{ $book->title }}</h4>
                                                <p class="text-xs text-gray-500">{{ $book->author?->name }}</p>
                                                <p class="text-sm font-bold text-[#DB8B00] mt-2">Rp {{ number_format((float)$book->final_price, 0, ',', '.') }}</p>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($authors->isNotEmpty())
                            <div>
                                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-4">Penulis ({{ $authors->count() }})</h3>
                                <div class="flex flex-wrap gap-3">
                                    @foreach($authors as $author)
                                        <a href="{{ route('bookstore.index', ['author' => $author->slug]) }}" class="px-4 py-2.5 rounded-xl border border-[#DDD8C8] hover:border-[#DB8B00] text-sm font-semibold">
                                            {{ $author->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($posts->isNotEmpty())
                            <div>
                                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-4">Artikel Blog ({{ $posts->count() }})</h3>
                                <div class="space-y-3">
                                    @foreach($posts as $post)
                                        <a href="{{ route('blog.show', $post->slug) }}" class="block p-4 rounded-2xl border border-[#DDD8C8] hover:border-[#DB8B00]">
                                            <h4 class="font-serif text-lg text-[#171512]">{{ $post->title }}</h4>
                                            <p class="text-xs text-gray-500 line-clamp-1 mt-1">{{ $post->excerpt }}</p>
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
