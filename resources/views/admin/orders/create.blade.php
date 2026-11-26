@extends('layouts.admin')

@section('title', 'Buat Pesanan Manual | Admin Mega Press')
@section('breadcrumb', 'Pesanan / Buat Manual')
@section('page_title', 'Buat Pesanan Manual')
@section('page_description', 'Catat penjualan langsung di toko atau transaksi offline.')

@section('content')
<form method="POST" action="{{ route('admin.orders.store') }}" class="max-w-3xl space-y-6"
      x-data="{ rows: [{ book_id: '{{ $books->first()?->id }}', qty: 1 }] }">
    @csrf

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <x-input name="customer_name" label="Nama Pelanggan" required />
        <x-input name="customer_phone" label="No. HP / WhatsApp" required />
    </div>

    <div>
        <label class="block text-xs font-bold uppercase tracking-wider mb-1.5">Alamat / Keterangan Pengambilan *</label>
        <textarea name="customer_address" rows="2" required class="w-full rounded-xl border border-[#DDD8C8] px-4 py-2.5 text-sm">{{ old('customer_address', 'Ambil langsung di toko') }}</textarea>
    </div>

    <div>
        <label class="block text-xs font-bold uppercase tracking-wider mb-2">Daftar Buku *</label>
        <div class="space-y-3">
            <template x-for="(row, idx) in rows" :key="idx">
                <div class="flex gap-3 items-center">
                    <select :name="`items[${idx}][book_id]`" x-model="row.book_id" class="flex-1 h-11 rounded-xl border border-[#DDD8C8] px-3 text-sm bg-white" required>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}">{{ $book->title }} (Stok: {{ $book->stock }} | Rp {{ number_format((float)$book->final_price, 0, ',', '.') }})</option>
                        @endforeach
                    </select>
                    <input type="number" :name="`items[${idx}][qty]`" x-model="row.qty" min="1" class="w-24 h-11 rounded-xl border border-[#DDD8C8] px-3 text-sm" required>
                    <button type="button" x-show="rows.length > 1" @click="rows.splice(idx, 1)" class="px-3 h-11 rounded-xl border border-red-200 text-red-600 text-xs font-bold">Hapus</button>
                </div>
            </template>
        </div>
        <button type="button" @click="rows.push({ book_id: '{{ $books->first()?->id }}', qty: 1 })"
                class="mt-3 text-xs font-bold text-[#DB8B00] hover:underline cursor-pointer">+ Tambah Baris Buku</button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider mb-1.5">Status Awal</label>
            <select name="status" class="w-full h-11 rounded-xl border border-[#DDD8C8] px-3 text-sm bg-white">
                <option value="confirmed">Dikonfirmasi (Langsung potong stok)</option>
                <option value="paid">Sudah Dibayar (Potong stok)</option>
                <option value="completed">Selesai (Potong stok)</option>
                <option value="pending">Pending (Belum potong stok)</option>
            </select>
        </div>
        <x-input name="notes" label="Catatan (Opsional)" />
    </div>

    <div class="flex gap-3 pt-2">
        <a href="{{ route('admin.orders.index') }}" class="px-5 py-2.5 rounded-xl border border-[#DDD8C8] text-sm font-semibold">Batal</a>
        <x-button type="submit" variant="primary">Simpan Pesanan</x-button>
    </div>
</form>
@endsection
