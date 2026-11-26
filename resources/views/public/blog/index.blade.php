@extends('layouts.app')

@section('title', 'Blog & Editorial | Workshop Indonesia Maju')
@section('meta_description', 'Artikel literasi, tips menulis, ulasan buku, dan kabar terbaru dari redaksi Workshop Indonesia Maju.')

@section('content')
<div class="bg-[#F3F4F7] min-h-screen pt-20 md:pt-32 pb-24 text-[#111218]">

    {{-- Architectural Editorial Header Band --}}
    <div class="border-b border-[#C9CEDF] bg-[#EAECEF]/70 relative overflow-hidden">
        <div aria-hidden="true" class="absolute inset-0 pointer-events-none wim-editorial-columns"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-16 relative z-10">
            <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-[0.2em] text-[#636778] mb-5">
                <span>Redaksi WIM</span>
                <span class="text-[#2B3288]">Jurnal &amp; Opini Literasi</span>
                <span class="hidden sm:inline">Arsip Terbuka</span>
            </div>
            <h1 class="font-serif text-3xl sm:text-6xl text-[#111218] uppercase leading-[0.94] tracking-[-0.04em]">
                Editorial <em class="italic font-normal lowercase text-[#2B3288]">&amp; jurnal</em> Literasi
            </h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
        {{-- Category Navigation Bar --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-10 pb-5 border-b border-[#111218]/25">
            <div class="flex items-center gap-3">
                <a href="{{ route('blog.index') }}" class="text-xs font-bold uppercase tracking-[0.16em] text-white bg-[#2B3288] px-5 py-2.5 inline-block">
                    Blog Index
                </a>
                @if($activeCategory || $activeTag)
                    <a href="{{ route('blog.index') }}" class="text-xs font-bold uppercase tracking-wider text-[#2B3288] hover:underline">
                        Reset Filter
                    </a>
                @endif
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('blog.index') }}"
                   class="px-4 py-2 text-xs font-bold uppercase tracking-wider border transition-colors {{ !$activeCategory ? 'bg-[#111218] text-white border-[#111218]' : 'bg-white text-[#636778] border-[#C9CEDF] hover:border-[#2B3288] hover:text-[#2B3288]' }}">
                    Semua
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('blog.index', ['category' => $cat->slug]) }}"
                       class="px-4 py-2 text-xs font-bold uppercase tracking-wider border transition-colors {{ $activeCategory === $cat->slug ? 'bg-[#2B3288] text-white border-[#2B3288]' : 'bg-white text-[#636778] border-[#C9CEDF] hover:border-[#2B3288] hover:text-[#2B3288]' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Hero Post --}}
        @if($heroPost)
            <a href="{{ route('blog.show', $heroPost->slug) }}"
               class="group grid grid-cols-1 lg:grid-cols-12 bg-white border border-[#C9CEDF] hover:border-[#2B3288] transition-colors mb-14 overflow-hidden">
                <div class="lg:col-span-7 aspect-[16/10] bg-[#DDE2F3] border-b lg:border-b-0 lg:border-r border-[#C9CEDF] overflow-hidden">
                    <img src="{{ $heroPost->featured_image ?: asset('images/wim-blog-editorial.jpg') }}"
                         alt="{{ $heroPost->title }}"
                         class="w-full h-full object-cover group-hover:scale-103 transition-transform duration-500">
                </div>
                <div class="lg:col-span-5 p-8 sm:p-10 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-[0.2em] text-[#2B3288] pb-3 border-b border-[#E3E7F3] mb-5">
                            <span>{{ $heroPost->category?->name ?? 'Editorial' }}</span>
                            <span>01 // Pilihan Utama</span>
                        </div>
                        <h2 class="font-serif text-2xl sm:text-4xl font-normal text-[#111218] group-hover:text-[#2B3288] transition-colors mb-4 leading-[1.08] tracking-[-0.02em]">
                            {{ $heroPost->title }}
                        </h2>
                        <p class="text-sm text-[#636778] line-clamp-3 mb-6 leading-relaxed">
                            {{ $heroPost->excerpt }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-[#E3E7F3] text-xs text-[#636778] flex flex-wrap items-center gap-3">
                        <span class="font-bold text-[#111218]">{{ $heroPost->author?->name ?? 'Redaksi Workshop Indonesia Maju' }}</span>
                        <span>&middot;</span>
                        <span>{{ optional($heroPost->publish_date ?? $heroPost->created_at)->translatedFormat('d M Y') }}</span>
                        @if($heroPost->reading_time)
                            <span>&middot;</span>
                            <span>{{ $heroPost->reading_time }} mnt baca</span>
                        @endif
                    </div>
                </div>
            </a>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            {{-- Latest Posts --}}
            <div class="lg:col-span-8">
                <div class="flex items-center justify-between border-t border-[#111218]/40 pt-3 pb-6 text-xs font-bold uppercase tracking-[0.18em]">
                    <span class="text-[#111218]">Artikel Terbaru</span>
                    <span class="text-[#2B3288]">{{ $posts->total() }} Tulisan</span>
                </div>

                @if($posts->isEmpty())
                    <div class="bg-white border border-dashed border-[#C9CEDF] py-16 text-center text-[#636778]">
                        Belum ada artikel yang dipublikasikan.
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        @foreach($posts as $post)
                            <a href="{{ route('blog.show', $post->slug) }}"
                               class="group bg-white border border-[#C9CEDF] hover:border-[#2B3288] overflow-hidden flex flex-col transition-colors">
                                <div class="aspect-[16/10] bg-[#DDE2F3] border-b border-[#C9CEDF] overflow-hidden">
                                    <img src="{{ $post->thumbnail ?: $post->featured_image ?: asset('images/wim-blog-editorial.jpg') }}"
                                         alt="{{ $post->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                                <div class="p-6 flex-1 flex flex-col justify-between">
                                    <div>
                                        <span class="text-[10px] font-bold uppercase tracking-[0.18em] text-[#2B3288]">
                                            {{ $post->category?->name ?? 'Artikel' }}
                                        </span>
                                        <h4 class="font-serif text-xl text-[#111218] group-hover:text-[#2B3288] transition-colors mt-2 mb-2.5 leading-snug line-clamp-2">
                                            {{ $post->title }}
                                        </h4>
                                        <p class="text-xs text-[#636778] line-clamp-2 mb-4 leading-relaxed">{{ $post->excerpt }}</p>
                                    </div>
                                    <div class="text-[11px] text-[#636778] flex items-center justify-between pt-3 border-t border-[#E3E7F3]">
                                        <span class="font-semibold text-[#111218]">{{ $post->author?->name ?? 'Redaksi' }}</span>
                                        <span>{{ optional($post->publish_date ?? $post->created_at)->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-10">
                        {{ $posts->links() }}
                    </div>
                @endif
            </div>

            {{-- Sidebar: Numbered Popular Index, Tags, Newsletter --}}
            <aside class="lg:col-span-4 space-y-6">
                <div class="bg-white border border-[#C9CEDF] p-6">
                    <h4 class="font-bold text-xs uppercase tracking-[0.18em] text-[#2B3288] pb-3 border-b border-[#111218]/25 mb-4">
                        Artikel Populer
                    </h4>
                    <div class="divide-y divide-[#C9CEDF]">
                        @foreach($popularPosts as $idx => $pop)
                            <a href="{{ route('blog.show', $pop->slug) }}" class="flex items-start justify-between gap-3 group py-3.5 first:pt-0 last:pb-0">
                                <div>
                                    <p class="font-serif text-base text-[#111218] group-hover:text-[#2B3288] transition-colors line-clamp-2 leading-snug">
                                        {{ $pop->title }}
                                    </p>
                                    <span class="text-[11px] text-[#636778] mt-1 block">{{ $pop->view_count }} kali dibaca</span>
                                </div>
                                <span class="text-xs font-bold text-[#C5CAE0] group-hover:text-[#2B3288] shrink-0">0{{ $idx + 1 }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                @if($tags->isNotEmpty())
                    <div class="bg-white border border-[#C9CEDF] p-6">
                        <h4 class="font-bold text-xs uppercase tracking-[0.18em] text-[#2B3288] pb-3 border-b border-[#111218]/25 mb-4">
                            Topik / Tag
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($tags as $tag)
                                <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}"
                                   class="px-3 py-1.5 text-xs font-semibold border transition-colors {{ $activeTag === $tag->slug ? 'bg-[#2B3288] text-white border-[#2B3288]' : 'bg-[#F3F4F7] text-[#636778] border-[#C9CEDF] hover:border-[#2B3288] hover:text-[#2B3288]' }}">
                                    #{{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="bg-[#2B3288] text-white border border-[#1F256D] p-6">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-white/65 mb-2">Buletin Redaksi</p>
                    <h4 class="font-serif text-2xl mb-2">Newsletter Redaksi</h4>
                    <p class="text-xs text-white/75 mb-5 leading-relaxed">Dapatkan update artikel kepenulisan &amp; buku baru Workshop Indonesia Maju.</p>
                    <form method="POST" action="{{ route('newsletter.subscribe') }}" class="space-y-2.5">
                        @csrf
                        <input type="email" name="email" required placeholder="Alamat email..."
                               class="w-full h-11 px-3.5 text-sm bg-white text-[#111218] focus:outline-none">
                        <button type="submit" class="w-full h-11 bg-[#111218] hover:bg-[#1F256D] text-white font-bold text-xs uppercase tracking-[0.15em] cursor-pointer transition-colors">
                            Langganan
                        </button>
                    </form>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection
