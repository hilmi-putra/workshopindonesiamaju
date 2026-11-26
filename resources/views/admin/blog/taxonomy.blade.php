@extends('layouts.admin')

@section('title', 'Kategori, Tag & Author Blog | Admin WIM')
@section('breadcrumb', 'Blog / Taksonomi')
@section('page_title', 'Kategori, Tag & Author Blog')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Blog Categories --}}
    <div class="space-y-4">
        <h2 class="font-bold text-base">Kategori Blog</h2>
        <form method="POST" action="{{ route('admin.blog.categories.store') }}" class="flex gap-2">
            @csrf
            <input type="text" name="name" placeholder="Kategori..." required class="flex-1 h-10 rounded-xl border border-[#D9DFEE] px-3 text-sm">
            <x-button type="submit" variant="primary" size="sm">+</x-button>
        </form>
        <div class="border border-[#E3E7F3] rounded-2xl divide-y divide-gray-100">
            @foreach($categories as $cat)
                <div class="p-3 flex justify-between items-center text-sm">
                    <span>{{ $cat->name }} <small class="text-gray-400">({{ $cat->posts_count }})</small></span>
                    <form method="POST"
                          action="{{ route('admin.blog.categories.destroy', $cat) }}"
                          data-confirm
                          data-confirm-title="Hapus Kategori Blog?"
                          data-confirm-message="Kategori blog ini akan dihapus secara permanen."
                          data-confirm-item="{{ $cat->name }}"
                          data-confirm-button="Ya, Hapus"
                          data-confirm-variant="danger">@csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-600 font-bold hover:underline cursor-pointer">Hapus</button>
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
            <input type="text" name="name" placeholder="Tag..." required class="flex-1 h-10 rounded-xl border border-[#D9DFEE] px-3 text-sm">
            <x-button type="submit" variant="primary" size="sm">+</x-button>
        </form>
        <div class="border border-[#E3E7F3] rounded-2xl divide-y divide-gray-100">
            @foreach($tags as $tag)
                <div class="p-3 flex justify-between items-center text-sm">
                    <span>#{{ $tag->name }} <small class="text-gray-400">({{ $tag->posts_count }})</small></span>
                    <form method="POST"
                          action="{{ route('admin.blog.tags.destroy', $tag) }}"
                          data-confirm
                          data-confirm-title="Hapus Tag Blog?"
                          data-confirm-message="Tag blog ini akan dihapus secara permanen."
                          data-confirm-item="#{{ $tag->name }}"
                          data-confirm-button="Ya, Hapus"
                          data-confirm-variant="danger">@csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-600 font-bold hover:underline cursor-pointer">Hapus</button>
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
            <input type="text" name="name" placeholder="Nama author..." required class="flex-1 h-10 rounded-xl border border-[#D9DFEE] px-3 text-sm">
            <x-button type="submit" variant="primary" size="sm">+</x-button>
        </form>
        <div class="border border-[#E3E7F3] rounded-2xl divide-y divide-gray-100">
            @foreach($authors as $author)
                <div class="p-3 flex justify-between items-center text-sm">
                    <span>{{ $author->name }} <small class="text-gray-400">({{ $author->posts_count }})</small></span>
                    <form method="POST"
                          action="{{ route('admin.blog.authors.destroy', $author) }}"
                          data-confirm
                          data-confirm-title="Hapus Author Blog?"
                          data-confirm-message="Data author blog ini akan dihapus permanen."
                          data-confirm-item="{{ $author->name }}"
                          data-confirm-button="Ya, Hapus"
                          data-confirm-variant="danger">@csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-600 font-bold hover:underline cursor-pointer">Hapus</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
