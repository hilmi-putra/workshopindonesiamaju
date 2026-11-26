@extends('layouts.admin')

@section('title', 'Kategori, Tag & Author Blog | Admin Mega Press')
@section('breadcrumb', 'Blog / Taksonomi')
@section('page_title', 'Kategori, Tag & Author Blog')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Blog Categories --}}
    <div class="space-y-4">
        <h2 class="font-bold text-base">Kategori Blog</h2>
        <form method="POST" action="{{ route('admin.blog.categories.store') }}" class="flex gap-2">
            @csrf
            <input type="text" name="name" placeholder="Kategori..." required class="flex-1 h-10 rounded-xl border border-[#DDD8C8] px-3 text-sm">
            <x-button type="submit" variant="primary" size="sm">+</x-button>
        </form>
        <div class="border border-gray-100 rounded-2xl divide-y divide-gray-100">
            @foreach($categories as $cat)
                <div class="p-3 flex justify-between items-center text-sm">
                    <span>{{ $cat->name }} <small class="text-gray-400">({{ $cat->posts_count }})</small></span>
                    <form method="POST" action="{{ route('admin.blog.categories.destroy', $cat) }}">@csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-600 font-bold cursor-pointer">Hapus</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Blog Tags --}}
    <div class="space-y-4">
        <h2 class="font-bold text-base">Tag Blog</h2>
        <form method="POST" action="{{ route('admin.blog.tags.store') }}" class="flex gap-2">
            @csrf
            <input type="text" name="name" placeholder="Tag..." required class="flex-1 h-10 rounded-xl border border-[#DDD8C8] px-3 text-sm">
            <x-button type="submit" variant="dark" size="sm">+</x-button>
        </form>
        <div class="border border-gray-100 rounded-2xl divide-y divide-gray-100">
            @foreach($tags as $tag)
                <div class="p-3 flex justify-between items-center text-sm">
                    <span>#{{ $tag->name }} <small class="text-gray-400">({{ $tag->posts_count }})</small></span>
                    <form method="POST" action="{{ route('admin.blog.tags.destroy', $tag) }}">@csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-600 font-bold cursor-pointer">Hapus</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Blog Authors --}}
    <div class="space-y-4">
        <h2 class="font-bold text-base">Author Blog</h2>
        <form method="POST" action="{{ route('admin.blog.authors.store') }}" class="flex gap-2">
            @csrf
            <input type="text" name="name" placeholder="Nama author..." required class="flex-1 h-10 rounded-xl border border-[#DDD8C8] px-3 text-sm">
            <x-button type="submit" variant="forest" size="sm">+</x-button>
        </form>
        <div class="border border-gray-100 rounded-2xl divide-y divide-gray-100">
            @foreach($authors as $author)
                <div class="p-3 flex justify-between items-center text-sm">
                    <span>{{ $author->name }} <small class="text-gray-400">({{ $author->posts_count }})</small></span>
                    <form method="POST" action="{{ route('admin.blog.authors.destroy', $author) }}">@csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-600 font-bold cursor-pointer">Hapus</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
