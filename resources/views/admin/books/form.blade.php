@extends('layouts.admin')

@section('title', ($book->exists ? 'Edit Buku' : 'Tambah Buku') . ' | Admin Mega Press')
@section('breadcrumb', $book->exists ? 'Buku / Edit' : 'Buku / Tambah')
@section('page_title', $book->exists ? 'Edit Buku: ' . $book->title : 'Tambah Buku Baru')

@section('content')
<form method="POST"
      action="{{ $book->exists ? route('admin.books.update', $book) : route('admin.books.store') }}"
      enctype="multipart/form-data"
      class="max-w-4xl space-y-6">
    @csrf
    @if($book->exists)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <x-input name="title" label="Judul Buku" :value="$book->title" required />
        <x-input name="subtitle" label="Subjudul" :value="$book->subtitle" />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <x-input name="isbn" label="ISBN" :value="$book->isbn" />
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider mb-1.5">Penulis *</label>
            <select name="author_id" required class="w-full h-11 rounded-xl border border-[#DDD8C8] px-3 text-sm bg-white">
                @foreach($authors as $author)
                    <option value="{{ $author->id }}" @selected(old('author_id', $book->author_id) == $author->id)>{{ $author->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider mb-1.5">Penerbit *</label>
            <select name="publisher_id" required class="w-full h-11 rounded-xl border border-[#DDD8C8] px-3 text-sm bg-white">
                @foreach($publishers as $pub)
                    <option value="{{ $pub->id }}" @selected(old('publisher_id', $book->publisher_id) == $pub->id)>{{ $pub->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <x-input type="number" step="0.01" name="price" label="Harga (Rp)" :value="$book->price" required />
        <x-input type="number" step="0.01" name="discount" label="Diskon (%)" :value="$book->discount ?? 0" />
        <x-input type="number" name="stock" label="Stok Awal" :value="$book->stock ?? 0" required />
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider mb-1.5">Status *</label>
            <select name="status" class="w-full h-11 rounded-xl border border-[#DDD8C8] px-3 text-sm bg-white">
                <option value="published" @selected(old('status', $book->status) === 'published')>Published</option>
                <option value="draft" @selected(old('status', $book->status) === 'draft')>Draft</option>
                <option value="archived" @selected(old('status', $book->status) === 'archived')>Archived</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <x-input type="number" name="page_count" label="Jml Halaman" :value="$book->page_count" />
        <x-input type="number" step="0.01" name="weight" label="Berat (gram)" :value="$book->weight" />
        <x-input name="dimensions" label="Dimensi (cm)" :value="$book->dimensions" placeholder="14 x 20 cm" />
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider mb-1.5">Cover Type</label>
            <select name="cover_type" class="w-full h-11 rounded-xl border border-[#DDD8C8] px-3 text-sm bg-white">
                <option value="softcover" @selected(old('cover_type', $book->cover_type) === 'softcover')>Softcover</option>
                <option value="hardcover" @selected(old('cover_type', $book->cover_type) === 'hardcover')>Hardcover</option>
                <option value="other" @selected(old('cover_type', $book->cover_type) === 'other')>Lainnya</option>
            </select>
        </div>
    </div>
    <input type="hidden" name="book_type" value="{{ old('book_type', $book->book_type ?? 'physical') }}">

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider mb-2">Kategori</label>
            <div class="grid grid-cols-2 gap-2 p-3 rounded-xl border border-[#DDD8C8] max-h-40 overflow-y-auto">
                @php $selectedCats = old('categories', $book->categories->pluck('id')->all()); @endphp
                @foreach($categories as $cat)
                    <label class="flex items-center gap-2 text-xs">
                        <input type="checkbox" name="categories[]" value="{{ $cat->id }}" @checked(in_array($cat->id, $selectedCats))>
                        <span>{{ $cat->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider mb-2">Genre</label>
            <div class="grid grid-cols-2 gap-2 p-3 rounded-xl border border-[#DDD8C8] max-h-40 overflow-y-auto">
                @php $selectedGenres = old('genres', $book->genres->pluck('id')->all()); @endphp
                @foreach($genres as $gen)
                    <label class="flex items-center gap-2 text-xs">
                        <input type="checkbox" name="genres[]" value="{{ $gen->id }}" @checked(in_array($gen->id, $selectedGenres))>
                        <span>{{ $gen->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider mb-1.5">Upload Cover Baru</label>
            <input type="file" name="cover_image_file" accept="image/*" class="w-full rounded-xl border border-dashed border-[#DDD8C8] px-3 py-2 text-sm">
        </div>
        <x-input name="cover_image" label="Atau URL Cover" :value="$book->cover_image" />
    </div>

    <div>
        <label class="block text-xs font-bold uppercase tracking-wider mb-1.5">Deskripsi Singkat</label>
        <textarea name="short_description" rows="2" class="w-full rounded-xl border border-[#DDD8C8] px-4 py-2.5 text-sm">{{ old('short_description', $book->short_description) }}</textarea>
    </div>

    <div>
        <label class="block text-xs font-bold uppercase tracking-wider mb-1.5">Sinopsis / Deskripsi Lengkap</label>
        <textarea name="long_description" rows="5" class="w-full rounded-xl border border-[#DDD8C8] px-4 py-2.5 text-sm">{{ old('long_description', $book->long_description) }}</textarea>
    </div>

    <div class="flex gap-6">
        <label class="flex items-center gap-2 text-sm font-medium">
            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $book->is_featured))>
            <span>Buku Pilihan (Featured)</span>
        </label>
        <label class="flex items-center gap-2 text-sm font-medium">
            <input type="checkbox" name="is_bestseller" value="1" @checked(old('is_bestseller', $book->is_bestseller))>
            <span>Bestseller</span>
        </label>
    </div>

    <div class="flex gap-3 pt-2">
        <a href="{{ route('admin.books.index') }}" class="px-5 py-2.5 rounded-xl border border-[#DDD8C8] text-sm font-semibold">Batal</a>
        <x-button type="submit" variant="primary">{{ $book->exists ? 'Simpan Perubahan' : 'Tambah Buku' }}</x-button>
    </div>
</form>
@endsection
