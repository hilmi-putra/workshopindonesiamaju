@extends('layouts.admin')

@section('title', 'Artikel Blog | Admin WIM')
@section('breadcrumb', 'Blog / Artikel')
@section('page_title', 'Manajemen Artikel Blog')

@section('page_actions')
    <a href="{{ route('admin.blog.posts.create') }}" class="px-5 py-2.5 rounded-xl bg-[#2B3288] hover:bg-[#20266E] text-white text-xs font-bold uppercase tracking-wider shadow-xs transition-colors">
        + Tulis Artikel
    </a>
@endsection

@section('content')
<div class="space-y-5">
    <form method="GET" action="{{ route('admin.blog.posts.index') }}" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul artikel..." class="flex-1 h-10 rounded-xl border border-[#D9DFEE] px-4 text-sm">
        <select name="status" class="h-10 rounded-xl border border-[#D9DFEE] px-3 text-sm">
            <option value="all">Semua Status</option>
            <option value="published" @selected(request('status') === 'published')>Published</option>
            <option value="draft" @selected(request('status') === 'draft')>Draft</option>
        </select>
        <button type="submit" class="px-5 h-10 rounded-xl bg-[#2B3288] hover:bg-[#20266E] text-white text-xs font-bold uppercase cursor-pointer transition-colors">Filter</button>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs uppercase text-gray-400 border-b border-gray-200">
                <tr>
                    <th class="py-3">Judul</th>
                    <th class="py-3">Kategori</th>
                    <th class="py-3">Author</th>
                    <th class="py-3">Status</th>
                    <th class="py-3">Views</th>
                    <th class="py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($posts as $post)
                    <tr>
                        <td class="py-3 font-bold">{{ $post->title }}</td>
                        <td class="py-3 text-xs">{{ $post->category?->name ?? '-' }}</td>
                        <td class="py-3 text-xs">{{ $post->author?->name ?? '-' }}</td>
                        <td class="py-3">
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase {{ $post->status === 'published' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $post->status }}
                            </span>
                        </td>
                        <td class="py-3 text-xs">{{ $post->view_count }}</td>
                        <td class="py-3 text-right space-x-2">
                            <a href="{{ route('admin.blog.posts.edit', $post) }}" class="text-xs font-bold text-[#2B3288] hover:underline">Edit</a>
                            <form method="POST"
                                  action="{{ route('admin.blog.posts.destroy', $post) }}"
                                  class="inline"
                                  data-confirm
                                  data-confirm-title="Hapus Artikel?"
                                  data-confirm-message="Artikel ini akan dihapus permanen dari blog."
                                  data-confirm-item="{{ $post->title }}"
                                  data-confirm-button="Ya, Hapus Artikel"
                                  data-confirm-variant="danger">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-red-600 hover:underline cursor-pointer">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-10 text-center text-gray-400">Belum ada artikel.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>{{ $posts->links() }}</div>
</div>
@endsection
