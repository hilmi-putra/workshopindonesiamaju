@extends('layouts.dashboard')

@section('title', 'Detail Pesanan ' . $order->display_code . ' | Workshop Indonesia Maju')

@section('dashboard_content')
@php
    $waAdmin = preg_replace('/[^0-9]/', '', (string) config('services.whatsapp.number', '6285724693474'));
    $contactWaUrl = 'https://wa.me/' . $waAdmin . '?text=' . rawurlencode("Halo Admin Workshop Indonesia Maju, saya ingin menanyakan pesanan dengan kode {$order->display_code}.");
@endphp

<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard.orders') }}" class="size-10 rounded-full border border-[#DDD8C8] bg-white flex items-center justify-center hover:bg-gray-50">&larr;</a>
            <div>
                <h1 class="text-2xl font-black text-[#171512] uppercase tracking-tighter">Detail Pesanan</h1>
                <p class="text-sm text-gray-500">Kode: <strong>{{ $order->display_code }}</strong></p>
            </div>
        </div>

        <a href="{{ $contactWaUrl }}" target="_blank" rel="noopener noreferrer"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#25D366] hover:bg-[#1ebe5d] text-white text-xs font-bold uppercase tracking-wider">
            Hubungi Admin via WA
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-[#DDD8C8] overflow-hidden">
        <div class="p-6 bg-gray-50/70 border-b border-gray-100 flex flex-wrap justify-between items-center gap-4">
            <div>
                <span class="block text-[10px] font-bold uppercase tracking-widest text-gray-400">Status Pesanan</span>
                <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $order->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : ($order->status === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-[#DB8B00]/15 text-[#DB8B00]') }}">
                    {{ $order->status_label }}
                </span>
            </div>
            <div class="text-sm text-gray-600">
                Dibuat pada {{ $order->created_at?->format('d M Y, H:i') }}
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 border-b border-gray-100 text-sm">
            <div>
                <h3 class="font-bold text-xs uppercase tracking-wider text-gray-400 mb-2">Info Pemesan</h3>
                <p class="font-bold text-[#171512]">{{ $order->customer_name }}</p>
                <p class="text-gray-600">{{ $order->customer_phone }}</p>
                <p class="text-gray-600 mt-1">{{ $order->customer_address }}</p>
                @if($order->notes)
                    <p class="text-xs text-gray-500 mt-2 italic">Catatan: {{ $order->notes }}</p>
                @endif
            </div>
            <div>
                <h3 class="font-bold text-xs uppercase tracking-wider text-gray-400 mb-2">Info Pembayaran &amp; Pengiriman</h3>
                <p class="text-gray-600">Ongkos kirim dan metode pembayaran dikonfirmasi bersama admin melalui WhatsApp.</p>
                <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="inline-block mt-3 text-xs font-bold text-[#DB8B00] hover:underline">
                    Kirim Ulang Format Pesanan ke WA &rarr;
                </a>
            </div>
        </div>

        <div class="p-6 space-y-4">
            <h3 class="font-bold text-xs uppercase tracking-wider text-gray-400">Daftar Buku</h3>
            <div class="divide-y divide-gray-100">
                @foreach($order->items as $item)
                    <div class="py-4 flex items-center justify-between gap-4">
                        <div>
                            <p class="font-bold text-[#171512]">{{ $item->display_title }}</p>
                            <p class="text-xs text-gray-500">{{ $item->display_qty }} &times; Rp {{ number_format($item->display_price, 0, ',', '.') }}</p>
                        </div>
                        <p class="font-bold text-[#DB8B00]">Rp {{ number_format($item->line_total, 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>

            <div class="pt-4 border-t border-gray-200 flex justify-between items-center">
                <span class="font-bold uppercase tracking-wider text-sm">Total Harga Buku</span>
                <span class="font-black text-xl text-[#171512]">Rp {{ number_format($order->display_total, 0, ',', '.') }}</span>
            </div>
        </div>

        @if($order->timeline->isNotEmpty())
            <div class="p-6 border-t border-gray-100 bg-[#FAF8F4]/60">
                <h3 class="font-bold text-xs uppercase tracking-wider text-gray-400 mb-4">Riwayat Status</h3>
                <div class="space-y-3">
                    @foreach($order->timeline as $event)
                        <div class="flex gap-3 text-xs">
                            <span class="size-2.5 rounded-full bg-[#DB8B00] mt-1 shrink-0"></span>
                            <div>
                                <p class="font-bold uppercase text-[#171512]">{{ $event->status }}</p>
                                <p class="text-gray-500">{{ $event->created_at?->format('d M Y, H:i') }} &mdash; {{ $event->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
