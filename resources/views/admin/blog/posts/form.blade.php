@extends('layouts.admin')

@section('title', ($post->exists ? 'Edit Artikel' : 'Tulis Artikel') . ' | Admin Mega Press')
@section('breadcrumb', 'Blog / ' . ($post->exists ? 'Edit' : 'Buat'))
@section('page_title', $post->exists ? 'Edit Artikel' : 'Tulis Artikel Baru')

@section('content')
<form method="POST"
      action="{{ $post->exists ? route('admin.blog.posts.update', $post) : route('admin.blog.posts.store') }}"
      enctype="multipart/form-data"
      class="max-w-4xl space-y-5">
    @csrf
    @if($post->exists) @method('PUT') @endif

    <x-input name="title" label="Judul Artikel" :value="$post->title" required />

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider mb-1.5">Kategori</label>
            <select name="blog_category_id" class="w-full h-11 rounded-xl border border-[#DDD8C8] px-3 text-sm bg-white">
                <option value="">Tanpa Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(old('blog_category_id', $post->blog_category_id) == $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider mb-1.5">Author</label>
            <select name="blog_author_id" class="w-full h-11 rounded-xl border border-[#DDD8C8] px-3 text-sm bg-white">
                <option value="">Redaksi</option>
                @foreach($authors as $author)
                    <option value="{{ $author->id }}" @selected(old('blog_author_id', $post->blog_author_id) == $author->id)>{{ $author->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider mb-1.5">Status</label>
            <select name="status" class="w-full h-11 rounded-xl border border-[#DDD8C8] px-3 text-sm bg-white">
                <option value="published" @selected(old('status', $post->status) === 'published')>Published</option>
                <option value="draft" @selected(old('status', $post->status) === 'draft')>Draft</option>
            </select>
        </div>
    </div>

    <div>
        <label class="block text-xs font-bold uppercase tracking-wider mb-1.5">Ringkasan (Excerpt)</label>
        <textarea name="excerpt" rows="2" class="w-full rounded-xl border border-[#DDD8C8] px-4 py-2 text-sm">{{ old('excerpt', $post->excerpt) }}</textarea>
    </div>

    <div>
        <label class="block text-xs font-bold uppercase tracking-wider mb-1.5">Konten Artikel (HTML / Teks) *</label>
        <textarea name="content" rows="12" required class="w-full rounded-xl border border-[#DDD8C8] px-4 py-3 text-sm font-mono">{{ old('content', $post->content) }}</textarea>
    </div>

    <div>
        <label class="block text-xs font-bold uppercase tracking-wider mb-2">Tags</label>
        <div class="flex flex-wrap gap-3">
            @php $selectedTags = old('tags', $post->tags->pluck('id')->all()); @endphp
            @foreach($tags as $tag)
                <label class="flex items-center gap-1.5 text-xs bg-[#FAF8F4] px-3 py-1.5 rounded-full border border-[#DDD8C8]">
                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" @checked(in_array($tag->id, $selectedTags))>
                    <span>{{ $tag->name }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider mb-1.5">Upload Gambar Utama</label>
            <input type="file" name="featured_image_file" accept="image/*" class="w-full rounded-xl border border-dashed border-[#DDD8C8] px-3 py-2 text-sm">
        </div>
        <x-input name="featured_image" label="Atau URL Gambar" :value="$post->featured_image" />
    </div>

    <label class="flex items-center gap-2 text-sm font-medium">
        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $post->is_featured))>
        <span>Tampilkan sebagai Featured Hero di Halaman Blog</span>
    </label>

    <div class="flex gap-3 pt-2">
        <a href="{{ route('admin.blog.posts.index') }}" class="px-5 py-2.5 rounded-xl border border-[#DDD8C8] text-sm font-semibold">Batal</a>
        <x-button type="submit" variant="primary">{{ $post->exists ? 'Simpan Perubahan' : 'Publikasikan Artikel' }}</x-button>
    </div>
</form>
@endsection
