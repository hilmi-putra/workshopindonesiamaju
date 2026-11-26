@extends('layouts.admin')

@section('title', 'Katalog Buku | Admin WIM')
@section('breadcrumb', 'Buku')
@section('page_title', 'Manajemen Buku')
@section('page_description', 'Tambah, ubah, atur status, dan pantau stok buku di WIM Bookstore.')

@section('page_actions')
    <a href="{{ route('admin.books.create') }}" class="px-5 py-2.5 rounded-xl bg-[#2B3288] hover:bg-[#20266E] text-white text-xs font-bold uppercase tracking-wider shadow-xs transition-colors">
        + Tambah Buku
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <form method="GET" action="{{ route('admin.books.index') }}" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau ISBN..."
               class="flex-1 min-w-[200px] h-10 rounded-xl border border-[#D9DFEE] px-4 text-sm">
        <select name="status" class="h-10 rounded-xl border border-[#D9DFEE] px-3 text-sm">
            <option value="all">Semua Status</option>
            <option value="published" @selected(request('status') === 'published')>Published</option>
            <option value="draft" @selected(request('status') === 'draft')>Draft</option>
            <option value="archived" @selected(request('status') === 'archived')>Archived</option>
        </select>
        <button type="submit" class="px-5 h-10 rounded-xl bg-[#2B3288] hover:bg-[#20266E] text-white text-xs font-bold uppercase cursor-pointer transition-colors">Cari</button>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs uppercase text-gray-400 border-b border-gray-200">
                <tr>
                    <th class="py-3 px-2">Buku</th>
                    <th class="py-3 px-2">Penulis</th>
                    <th class="py-3 px-2">Harga</th>
                    <th class="py-3 px-2">Stok</th>
                    <th class="py-3 px-2">Status</th>
                    <th class="py-3 px-2 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($books as $book)
                    <tr class="hover:bg-gray-50/70">
                        <td class="py-3 px-2">
                            <div class="flex items-center gap-3">
                                <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-10 h-14 object-cover rounded-xs bg-gray-100 shrink-0">
                                <div>
                                    <p class="font-bold text-[#171512]">{{ $book->title }}</p>
                                    <p class="text-xs text-gray-400">ISBN: {{ $book->isbn ?: '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-2 text-gray-600">{{ $book->author?->name ?? '-' }}</td>
                        <td class="py-3 px-2">
                            <p class="font-semibold text-[#171512]">Rp {{ number_format((float)$book->final_price, 0, ',', '.') }}</p>
                            @if((float) $book->discount > 0 && (float) $book->final_price < (float) $book->price)
                                <p class="text-[11px] text-gray-400">
                                    <span class="line-through">Rp {{ number_format((float)$book->price, 0, ',', '.') }}</span>
                                    <span class="ml-1 font-bold text-emerald-700">-{{ rtrim(rtrim(number_format((float)$book->discount, 2, ',', '.'), '0'), ',') }}%</span>
                                </p>
                            @endif
                        </td>
                        <td class="py-3 px-2 font-bold {{ $book->stock <= 5 ? 'text-red-600' : 'text-emerald-700' }}">{{ $book->stock }}</td>
                        <td class="py-3 px-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase {{ $book->status === 'published' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $book->status }}
                            </span>
                        </td>
                        <td class="py-3 px-2 text-right space-x-2">
                            <a href="{{ route('admin.books.edit', $book) }}" class="text-xs font-bold text-[#2B3288] hover:underline">Edit</a>
                            <form method="POST"
                                  action="{{ route('admin.books.destroy', $book) }}"
                                  class="inline"
                                  data-confirm
                                  data-confirm-title="Hapus Buku?"
                                  data-confirm-message="Buku ini akan dihapus permanen dari katalog WIM Bookstore."
                                  data-confirm-item="{{ $book->title }}"
                                  data-confirm-button="Ya, Hapus Buku"
                                  data-confirm-variant="danger">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-red-600 hover:underline cursor-pointer">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-12 text-center text-gray-400">Tidak ada buku.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $books->links() }}</div>
</div>
@endsection
