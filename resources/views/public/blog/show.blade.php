@extends('layouts.app')

@section('title', ($post->meta_title ?: $post->title) . ' | Blog Workshop Indonesia Maju')
@section('meta_description', $post->meta_description ?: $post->excerpt)

@section('meta')
    <meta property="og:title" content="{{ $post->meta_title ?: $post->title }}">
    <meta property="og:description" content="{{ $post->meta_description ?: $post->excerpt }}">
    @if($post->og_image || $post->featured_image)
        <meta property="og:image" content="{{ $post->og_image ?: $post->featured_image }}">
    @endif
@endsection

@section('content')
<article class="bg-[#F3F4F7] min-h-screen pt-20 sm:pt-28 md:pt-36 pb-24 text-[#111218]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between border-t border-[#111218]/40 pt-3 pb-6">
            <a href="{{ route('blog.index') }}" class="text-xs font-bold uppercase tracking-[0.16em] text-[#2B3288] hover:underline">
                &larr; Kembali ke Blog
            </a>
            <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#636778]">WIM Editorial</span>
        </div>

        <header class="mb-8">
            @if($post->category)
                <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}"
                   class="inline-block px-4 py-1 bg-[#2B3288] text-white text-[11px] font-bold uppercase tracking-[0.16em] mb-4">
                    {{ $post->category->name }}
                </a>
            @endif

            <h1 class="font-serif text-3xl sm:text-5xl text-[#111218] leading-[1.06] tracking-[-0.03em] mb-6">
                {{ $post->title }}
            </h1>

            <div class="flex flex-wrap items-center gap-4 text-xs text-[#636778] py-4 border-y border-[#C9CEDF]">
                <span class="font-bold text-[#111218]">{{ $post->author?->name ?? 'Redaksi Workshop Indonesia Maju' }}</span>
                <span>&middot;</span>
                <span>{{ optional($post->publish_date ?? $post->created_at)->translatedFormat('d F Y') }}</span>
                @if($post->reading_time)
                    <span>&middot;</span>
                    <span>{{ $post->reading_time }} menit baca</span>
                @endif
                <span>&middot;</span>
                <span class="text-[#2B3288] font-semibold">{{ $post->view_count }} kali dibaca</span>
            </div>
        </header>

        @if($post->featured_image)
            <div class="bg-[#DDE2F3] border border-[#C9CEDF] overflow-hidden mb-10">
                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full max-h-[480px] object-cover">
            </div>
        @endif

        <div class="bg-white border border-[#C9CEDF] p-6 sm:p-10 text-[#111218]/90 leading-relaxed space-y-5 text-base">
            {!! $post->content !!}
        </div>

        @if($post->tags->isNotEmpty())
            <div class="mt-8 flex flex-wrap gap-2">
                @foreach($post->tags as $tag)
                    <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}"
                       class="px-3.5 py-1.5 bg-white border border-[#C9CEDF] text-xs font-semibold text-[#636778] hover:border-[#2B3288] hover:text-[#2B3288] transition-colors">
                        #{{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif

        @if($post->cta)
            <div class="mt-10 bg-[#2B3288] text-white border border-[#1F256D] p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-white/65 mb-1">Workshop Indonesia Maju</p>
                    <h3 class="font-serif text-2xl sm:text-3xl mb-1">{{ $post->cta->title }}</h3>
                    <p class="text-sm text-white/80">{{ $post->cta->description }}</p>
                </div>
                <a href="{{ $post->cta->button_link }}" class="px-6 py-3.5 bg-white text-[#2B3288] hover:bg-[#E7EBF7] font-bold text-xs uppercase tracking-[0.14em] shrink-0 transition-colors">
                    {{ $post->cta->button_text }} &rarr;
                </a>
            </div>
        @endif

        @if($relatedPosts->isNotEmpty())
            <div class="mt-16 pt-6 border-t border-[#111218]/30">
                <h3 class="font-serif text-2xl sm:text-3xl text-[#111218] mb-6">Artikel Terkait</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 border-t border-l border-[#C9CEDF] bg-white">
                    @foreach($relatedPosts as $idx => $rel)
                        <a href="{{ route('blog.show', $rel->slug) }}" class="group border-r border-b border-[#C9CEDF] p-6 hover:bg-[#F3F4F7] transition-colors flex flex-col justify-between gap-4">
                            <div>
                                <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-[0.18em] text-[#2B3288] mb-2">
                                    <span>{{ $rel->category?->name ?? 'Artikel' }}</span>
                                    <span class="text-[#C5CAE0] group-hover:text-[#2B3288]">0{{ $idx + 1 }}</span>
                                </div>
                                <h4 class="font-serif text-lg text-[#111218] group-hover:text-[#2B3288] line-clamp-2">{{ $rel->title }}</h4>
                            </div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-[#2B3288]">Baca &rarr;</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</article>
@endsection
