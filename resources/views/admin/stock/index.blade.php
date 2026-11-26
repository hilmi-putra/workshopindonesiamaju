@extends('layouts.admin')

@section('title', 'Stok & Mutasi | Admin Mega Press')
@section('breadcrumb', 'Stok & Mutasi')
@section('page_title', 'Manajemen Stok & Riwayat Mutasi')
@section('page_description', 'Penyesuaian stok manual tercatat otomatis pada riwayat stock_movements.')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <div class="lg:col-span-4">
        <div class="border border-gray-100 rounded-2xl p-6 bg-[#FAF8F4]">
            <h2 class="font-bold text-base text-[#171512] mb-4">Penyesuaian Stok Manual</h2>
            <form method="POST" action="{{ route('admin.stock.adjust') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-1.5">Pilih Buku *</label>
                    <select name="book_id" required class="w-full h-11 rounded-xl border border-[#DDD8C8] bg-white px-3 text-sm">
                        @foreach($books as $book)
                            <option value="{{ $book->id }}">{{ $book->title }} (Stok: {{ $book->stock }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-1.5">Perubahan (+ tambah / - kurang) *</label>
                    <input type="number" name="change" required placeholder="Misal: 20 atau -2" class="w-full h-11 rounded-xl border border-[#DDD8C8] bg-white px-4 text-sm">
                </div>

                <x-input name="reason" label="Alasan Penyesuaian" placeholder="Misal: Cetak ulang / Stok opname / Rusak" required />

                <x-button type="submit" variant="primary" class="w-full">Simpan Penyesuaian</x-button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-8 space-y-4">
        <form method="GET" action="{{ route('admin.stock.index') }}" class="flex gap-2">
            <select name="book_id" class="h-10 rounded-xl border border-gray-200 bg-white px-3 text-sm flex-1">
                <option value="">Semua Buku</option>
                @foreach($books as $book)
                    <option value="{{ $book->id }}" @selected(request('book_id') == $book->id)>{{ $book->title }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-5 h-10 rounded-xl bg-[#171512] text-white text-xs font-bold uppercase cursor-pointer">Filter</button>
        </form>

        <div class="overflow-x-auto border border-gray-100 rounded-2xl">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase text-gray-400 bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="py-3 px-4">Waktu</th>
                        <th class="py-3 px-4">Buku</th>
                        <th class="py-3 px-4">Perubahan</th>
                        <th class="py-3 px-4">Sisa</th>
                        <th class="py-3 px-4">Alasan</th>
                        <th class="py-3 px-4">Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($movements as $mv)
                        <tr>
                            <td class="py-3 px-4 text-xs text-gray-500">{{ $mv->created_at?->format('d/m/Y H:i') }}</td>
                            <td class="py-3 px-4 font-semibold">{{ $mv->book?->title ?? 'Buku #' . $mv->book_id }}</td>
                            <td class="py-3 px-4 font-bold {{ $mv->change < 0 ? 'text-red-600' : 'text-emerald-700' }}">
                                {{ $mv->change > 0 ? '+' . $mv->change : $mv->change }}
                            </td>
                            <td class="py-3 px-4 font-bold">{{ $mv->stock_after }}</td>
                            <td class="py-3 px-4 text-xs">
                                {{ $mv->reason }}
                                @if($mv->order)
                                    <a href="{{ route('admin.orders.show', $mv->order) }}" class="text-[#DB8B00] underline">({{ $mv->order->display_code }})</a>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-xs text-gray-500">{{ $mv->admin?->name ?? 'Sistem' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-10 text-center text-gray-400">Belum ada riwayat pergerakan stok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $movements->links() }}</div>
    </div>
</div>
@endsection
