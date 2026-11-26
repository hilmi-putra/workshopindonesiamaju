@extends('layouts.admin')

@section('title', 'Detail Pesanan ' . $order->display_code)
@section('breadcrumb', 'Pesanan / ' . $order->display_code)
@section('page_title', 'Pesanan ' . $order->display_code)
@section('page_description', 'Dibuat ' . $order->created_at?->format('d M Y, H:i'))

@section('page_actions')
    <div class="flex flex-wrap gap-2">
        @if($waChatUrl)
            <a href="{{ $waChatUrl }}" target="_blank" rel="noopener noreferrer"
               class="px-4 py-2 rounded-xl bg-[#25D366] hover:bg-[#1ebe5d] text-white text-xs font-bold uppercase tracking-wider">
                Chat Pelanggan via WA
            </a>
        @endif
        <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank"
           class="px-4 py-2 rounded-xl border border-gray-200 text-xs font-bold uppercase tracking-wider hover:bg-gray-50">
            Invoice PDF
        </a>
        <a href="{{ route('admin.orders.packing-slip', $order) }}" target="_blank"
           class="px-4 py-2 rounded-xl border border-gray-200 text-xs font-bold uppercase tracking-wider hover:bg-gray-50">
            Packing Slip
        </a>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <div class="lg:col-span-8 space-y-6">
        {{-- Customer Info --}}
        <div class="p-6 rounded-2xl border border-gray-100 bg-[#FAF8F4] text-sm space-y-1">
            <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Data Pemesan</p>
            <p class="font-bold text-base text-[#171512]">{{ $order->customer_name }}</p>
            <p class="text-gray-600">HP/WA: {{ $order->customer_phone }}</p>
            <p class="text-gray-600">Alamat: {{ $order->customer_address }}</p>
            @if($order->notes)
                <p class="text-xs text-gray-500 italic pt-1">Catatan: {{ $order->notes }}</p>
            @endif
        </div>

        {{-- Order Items --}}
        <div class="border border-gray-100 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 font-bold text-xs uppercase tracking-wider text-gray-500">
                Item Buku
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($order->items as $item)
                    <div class="p-5 flex justify-between items-center gap-4 text-sm">
                        <div>
                            <p class="font-bold text-[#171512]">{{ $item->display_title }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $item->display_qty }} eks &times; Rp {{ number_format($item->display_price, 0, ',', '.') }}
                                @if($item->book)
                                    &middot; Stok katalog saat ini: <strong>{{ $item->book->stock }}</strong>
                                @endif
                            </p>
                        </div>
                        <p class="font-bold text-[#171512]">Rp {{ number_format($item->line_total, 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
            <div class="px-6 py-4 bg-[#FAF8F4] border-t border-gray-100 flex justify-between items-center">
                <span class="font-bold text-sm uppercase">Total Harga Buku</span>
                <span class="font-black text-xl text-[#DB8B00]">Rp {{ number_format($order->display_total, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Stock Movements for this order --}}
        <div class="border border-gray-100 rounded-2xl p-6">
            <h3 class="font-bold text-xs uppercase tracking-wider text-gray-400 mb-3">Mutasi Stok Terkait Pesanan Ini</h3>
            @forelse($order->stockMovements as $mv)
                <div class="py-2 border-b border-gray-100 last:border-0 flex justify-between text-xs">
                    <span>{{ $mv->book?->title ?? 'Buku #' . $mv->book_id }} ({{ $mv->reason }})</span>
                    <span class="font-bold {{ $mv->change < 0 ? 'text-red-600' : 'text-emerald-700' }}">
                        {{ $mv->change > 0 ? '+' . $mv->change : $mv->change }} &rarr; sisa {{ $mv->stock_after }}
                    </span>
                </div>
            @empty
                <p class="text-xs text-gray-400">Belum ada pengurangan stok (stok berkurang otomatis saat status diubah ke Confirmed).</p>
            @endforelse
        </div>
    </div>

    {{-- Right Sidebar: Status Transition & Timeline --}}
    <div class="lg:col-span-4 space-y-6">
        <div class="border border-gray-100 rounded-2xl p-6 bg-white shadow-xs">
            <h3 class="font-bold text-sm uppercase tracking-wider text-[#171512] mb-1">Ubah Status Pesanan</h3>
            <p class="text-xs text-gray-500 mb-4">
                Status saat ini: <strong class="text-[#DB8B00]">{{ $order->status_label }}</strong>.
                Stok dikurangi otomatis saat <em>Confirmed</em> dan dikembalikan jika <em>Cancelled</em>.
            </p>

            <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-1">Status Baru</label>
                    <select name="status" class="w-full h-11 rounded-xl border border-[#DDD8C8] px-3 text-sm bg-white">
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" @selected($order->status === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-1">Catatan Audit (Opsional)</label>
                    <input type="text" name="note" placeholder="Misal: Sudah transfer BCA..." class="w-full h-10 rounded-xl border border-[#DDD8C8] px-3 text-sm">
                </div>

                <x-button type="submit" variant="primary" class="w-full">Simpan Perubahan Status</x-button>
            </form>
        </div>

        <div class="border border-gray-100 rounded-2xl p-6 bg-[#FAF8F4]">
            <h3 class="font-bold text-xs uppercase tracking-wider text-gray-400 mb-4">Audit Timeline</h3>
            <div class="space-y-3">
                @forelse($order->timeline as $event)
                    <div class="text-xs border-l-2 border-[#DB8B00] pl-3">
                        <p class="font-bold uppercase text-[#171512]">{{ $event->status }}</p>
                        <p class="text-gray-600">{{ $event->description }}</p>
                        <p class="text-gray-400 mt-0.5">{{ $event->created_at?->format('d M Y, H:i') }} &middot; {{ $event->actor_name ?? 'Sistem' }}</p>
                    </div>
                @empty
                    <p class="text-xs text-gray-400">Belum ada riwayat.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
