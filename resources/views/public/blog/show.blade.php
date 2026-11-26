@extends('layouts.app')

@section('title', ($post->meta_title ?: $post->title) . ' | Blog Mega Press')
@section('meta_description', $post->meta_description ?: $post->excerpt)

@section('meta')
    <meta property="og:title" content="{{ $post->meta_title ?: $post->title }}">
    <meta property="og:description" content="{{ $post->meta_description ?: $post->excerpt }}">
    @if($post->og_image || $post->featured_image)
        <meta property="og:image" content="{{ $post->og_image ?: $post->featured_image }}">
    @endif
@endsection

@section('content')
<article class="bg-[#FAF8F4] min-h-screen pt-28 md:pt-36 pb-24">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        <div class="mb-6">
            <a href="{{ route('blog.index') }}" class="text-xs font-bold uppercase tracking-wider text-[#DB8B00] hover:underline">
                &larr; Kembali ke Blog
            </a>
        </div>

        <header class="mb-8">
            @if($post->category)
                <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}"
                   class="inline-block px-4 py-1 rounded-full bg-[#DB8B00]/10 text-[#DB8B00] text-xs font-bold uppercase tracking-wider mb-4">
                    {{ $post->category->name }}
                </a>
            @endif

            <h1 class="font-serif text-3xl sm:text-5xl text-[#171512] leading-tight mb-5">
                {{ $post->title }}
            </h1>

            <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 pb-6 border-b border-[#DDD8C8]">
                <span class="font-bold text-[#171512]">{{ $post->author?->name ?? 'Redaksi Mega Press' }}</span>
                <span>&middot;</span>
                <span>{{ optional($post->publish_date ?? $post->created_at)->translatedFormat('d F Y') }}</span>
                @if($post->reading_time)
                    <span>&middot;</span>
                    <span>{{ $post->reading_time }} menit baca</span>
                @endif
                <span>&middot;</span>
                <span>{{ $post->view_count }} kali dibaca</span>
            </div>
        </header>

        @if($post->featured_image)
            <div class="rounded-3xl overflow-hidden mb-10 border border-[#DDD8C8]">
                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full max-h-[480px] object-cover">
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-[#DDD8C8] p-6 sm:p-10 text-[#171512]/90 leading-relaxed space-y-5 text-base">
            {!! $post->content !!}
        </div>

        @if($post->tags->isNotEmpty())
            <div class="mt-8 flex flex-wrap gap-2">
                @foreach($post->tags as $tag)
                    <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}"
                       class="px-3.5 py-1.5 rounded-full bg-white border border-[#DDD8C8] text-xs text-gray-600 hover:border-[#DB8B00]">
                        #{{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif

        @if($post->cta)
            <div class="mt-10 bg-[#084c3c] text-white rounded-3xl p-8 flex flex-col sm:flex-row items-center justify-between gap-6">
                <div>
                    <h3 class="font-serif text-2xl mb-1">{{ $post->cta->title }}</h3>
                    <p class="text-sm text-white/80">{{ $post->cta->description }}</p>
                </div>
                <a href="{{ $post->cta->button_link }}" class="px-6 py-3 rounded-full bg-[#DB8B00] text-white font-bold text-xs uppercase tracking-wider shrink-0">
                    {{ $post->cta->button_text }}
                </a>
            </div>
        @endif

        @if($relatedPosts->isNotEmpty())
            <div class="mt-16 pt-10 border-t border-[#DDD8C8]">
                <h3 class="font-serif text-2xl text-[#171512] mb-6">Artikel Terkait</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    @foreach($relatedPosts as $rel)
                        <a href="{{ route('blog.show', $rel->slug) }}" class="bg-white rounded-2xl border border-[#DDD8C8] p-5 hover:shadow-md transition-shadow">
                            <span class="text-[10px] font-bold uppercase text-[#DB8B00]">{{ $rel->category?->name ?? 'Artikel' }}</span>
                            <h4 class="font-serif text-lg text-[#171512] mt-1 line-clamp-2">{{ $rel->title }}</h4>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</article>
@endsection
