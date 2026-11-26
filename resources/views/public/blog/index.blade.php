@extends('layouts.app')

@section('title', 'Blog & Editorial | Mega Press')
@section('meta_description', 'Artikel literasi, tips menulis, ulasan buku, dan kabar terbaru dari redaksi Mega Press.')

@section('content')
<div class="bg-[#FAF8F4] min-h-screen pt-28 md:pt-32 pb-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header & Category Navigation (Matches Next.js BlogIndexPage) --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-12 pb-6 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <a href="{{ route('blog.index') }}" class="text-base font-bold text-white bg-[#DB8B00] px-6 py-2 rounded-full inline-block">
                    Blog
                </a>
                @if($activeCategory || $activeTag)
                    <a href="{{ route('blog.index') }}" class="text-xs font-semibold text-gray-500 hover:text-[#171512] underline">
                        Reset Filter
                    </a>
                @endif
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('blog.index') }}"
                   class="px-4 py-1.5 rounded-full text-xs font-semibold border transition-colors {{ !$activeCategory ? 'bg-[#171512] text-white border-[#171512]' : 'bg-white text-gray-600 border-[#DDD8C8] hover:border-[#DB8B00]' }}">
                    Semua
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('blog.index', ['category' => $cat->slug]) }}"
                       class="px-4 py-1.5 rounded-full text-xs font-semibold border transition-colors {{ $activeCategory === $cat->slug ? 'bg-[#171512] text-white border-[#171512]' : 'bg-white text-gray-600 border-[#DDD8C8] hover:border-[#DB8B00]' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Hero Post --}}
        @if($heroPost)
            <a href="{{ route('blog.show', $heroPost->slug) }}" class="group grid grid-cols-1 lg:grid-cols-12 gap-8 bg-white rounded-3xl border border-[#DDD8C8] overflow-hidden shadow-xs mb-14 hover:shadow-md transition-shadow">
                <div class="lg:col-span-7 aspect-[16/10] bg-[#EFEADD] overflow-hidden">
                    <img src="{{ $heroPost->featured_image ?: 'https://placehold.co/900x560/EFEADD/171512?text=Mega+Press+Editorial' }}"
                         alt="{{ $heroPost->title }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="lg:col-span-5 p-8 flex flex-col justify-center">
                    <span class="text-xs font-bold uppercase tracking-widest text-[#DB8B00] mb-3">
                        {{ $heroPost->category?->name ?? 'Editorial' }}
                    </span>
                    <h2 class="font-serif text-2xl sm:text-3xl font-bold text-[#171512] group-hover:text-[#DB8B00] transition-colors mb-4 leading-snug">
                        {{ $heroPost->title }}
                    </h2>
                    <p class="text-sm text-[#6B6860] line-clamp-3 mb-6 leading-relaxed">
                        {{ $heroPost->excerpt }}
                    </p>
                    <div class="text-xs text-gray-500 flex items-center gap-3">
                        <span class="font-semibold text-[#171512]">{{ $heroPost->author?->name ?? 'Redaksi Mega Press' }}</span>
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
                <h3 class="font-serif text-2xl text-[#171512] mb-6">Artikel Terbaru</h3>

                @if($posts->isEmpty())
                    <div class="bg-white rounded-2xl border border-dashed border-[#DDD8C8] py-16 text-center text-gray-500">
                        Belum ada artikel yang dipublikasikan.
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        @foreach($posts as $post)
                            <a href="{{ route('blog.show', $post->slug) }}" class="group bg-white rounded-2xl border border-[#DDD8C8] overflow-hidden flex flex-col hover:shadow-md transition-shadow">
                                <div class="aspect-[16/10] bg-[#EFEADD] overflow-hidden">
                                    <img src="{{ $post->thumbnail ?: $post->featured_image ?: 'https://placehold.co/600x375/EFEADD/171512?text=Blog' }}"
                                         alt="{{ $post->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                                <div class="p-5 flex-1 flex flex-col justify-between">
                                    <div>
                                        <span class="text-[11px] font-bold uppercase tracking-wider text-[#DB8B00]">
                                            {{ $post->category?->name ?? 'Artikel' }}
                                        </span>
                                        <h4 class="font-serif text-xl font-bold text-[#171512] group-hover:text-[#DB8B00] transition-colors mt-1.5 mb-2 line-clamp-2">
                                            {{ $post->title }}
                                        </h4>
                                        <p class="text-xs text-[#6B6860] line-clamp-2 mb-4">{{ $post->excerpt }}</p>
                                    </div>
                                    <div class="text-[11px] text-gray-400 flex items-center justify-between pt-3 border-t border-gray-100">
                                        <span>{{ $post->author?->name ?? 'Redaksi' }}</span>
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

            {{-- Sidebar: Popular, Tags, Newsletter --}}
            <aside class="lg:col-span-4 space-y-8">
                <div class="bg-white rounded-2xl border border-[#DDD8C8] p-6">
                    <h4 class="font-bold text-xs uppercase tracking-widest text-[#171512] mb-4">Artikel Populer</h4>
                    <div class="space-y-4">
                        @foreach($popularPosts as $pop)
                            <a href="{{ route('blog.show', $pop->slug) }}" class="block group border-b border-gray-100 last:border-0 pb-3 last:pb-0">
                                <p class="font-serif text-base text-[#171512] group-hover:text-[#DB8B00] transition-colors line-clamp-2">
                                    {{ $pop->title }}
                                </p>
                                <span class="text-[11px] text-gray-400">{{ $pop->view_count }} kali dibaca</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                @if($tags->isNotEmpty())
                    <div class="bg-white rounded-2xl border border-[#DDD8C8] p-6">
                        <h4 class="font-bold text-xs uppercase tracking-widest text-[#171512] mb-4">Topik / Tag</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($tags as $tag)
                                <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}"
                                   class="px-3 py-1 rounded-full text-xs border {{ $activeTag === $tag->slug ? 'bg-[#DB8B00] text-white border-[#DB8B00]' : 'bg-[#FAF8F4] text-gray-600 border-[#DDD8C8] hover:border-[#DB8B00]' }}">
                                    #{{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="bg-[#084c3c] text-white rounded-2xl p-6">
                    <h4 class="font-serif text-xl mb-2">Newsletter Redaksi</h4>
                    <p class="text-xs text-white/75 mb-4">Dapatkan update artikel kepenulisan &amp; buku baru.</p>
                    <form method="POST" action="{{ route('newsletter.subscribe') }}" class="space-y-2.5">
                        @csrf
                        <input type="email" name="email" required placeholder="Alamat email..."
                               class="w-full h-10 rounded-xl px-3.5 text-sm bg-white text-[#171512]">
                        <button type="submit" class="w-full h-10 rounded-xl bg-[#DB8B00] hover:bg-[#b06f00] text-white font-bold text-xs uppercase tracking-wider cursor-pointer">
                            Langganan
                        </button>
                    </form>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection
