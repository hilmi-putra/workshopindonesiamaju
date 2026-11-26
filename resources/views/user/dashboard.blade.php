@extends('layouts.dashboard')

@section('title', 'Dashboard Pelanggan | Mega Press')

@section('dashboard_content')
<div class="bg-white rounded-2xl border border-[#DDD8C8] overflow-hidden">
    <div class="p-6 md:p-8 border-b border-gray-100">
        <h1 class="text-2xl font-black text-[#171512] uppercase tracking-tighter mb-1">Dashboard</h1>
        <p class="text-sm text-gray-500">Ringkasan aktivitas akun dan pesanan WhatsApp Anda.</p>
    </div>

    <div class="p-6 md:p-8 space-y-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="border border-gray-100 rounded-2xl p-5 bg-[#FAF8F4]">
                <p class="text-xs font-semibold text-gray-500">Total Pesanan</p>
                <p class="text-2xl font-bold text-[#171512] mt-1">{{ $totalOrders }}</p>
            </div>
            <div class="border border-gray-100 rounded-2xl p-5 bg-[#FAF8F4]">
                <p class="text-xs font-semibold text-gray-500">Buku Dibeli</p>
                <p class="text-2xl font-bold text-emerald-700 mt-1">{{ $booksPurchased }}</p>
            </div>
            <div class="border border-gray-100 rounded-2xl p-5 bg-[#FAF8F4]">
                <p class="text-xs font-semibold text-gray-500">Dalam Proses</p>
                <p class="text-2xl font-bold text-[#DB8B00] mt-1">{{ $pendingOrders }}</p>
            </div>
            <div class="border border-gray-100 rounded-2xl p-5 bg-[#FAF8F4]">
                <p class="text-xs font-semibold text-gray-500">Buku Disimpan</p>
                <p class="text-2xl font-bold text-[#6E0000] mt-1">{{ $wishlistCount }}</p>
            </div>
        </div>

        <div>
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-[#171512]">Pesanan Terbaru</h2>
                <a href="{{ route('dashboard.orders') }}" class="text-xs font-bold text-[#DB8B00] hover:underline uppercase tracking-wider">Lihat Semua</a>
            </div>

            @forelse($recentOrders as $order)
                <div class="flex flex-wrap justify-between items-center gap-4 p-4 mb-3 border border-gray-100 rounded-xl bg-gray-50/60">
                    <div>
                        <p class="font-bold text-sm text-[#171512]">{{ $order->display_code }}</p>
                        <p class="text-xs text-gray-500">{{ $order->created_at?->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="font-bold text-sm text-[#171512]">Rp {{ number_format($order->display_total, 0, ',', '.') }}</p>
                            <span class="text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider bg-[#DB8B00]/10 text-[#DB8B00]">
                                {{ $order->status_label }}
                            </span>
                        </div>
                        <a href="{{ route('dashboard.orders.show', $order->display_code) }}" class="px-4 py-2 border border-gray-200 text-xs font-bold uppercase tracking-wider text-gray-700 hover:bg-white rounded-lg">
                            Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="py-10 text-center border border-dashed border-gray-200 rounded-xl">
                    <p class="text-gray-500 text-sm mb-4">Belum ada pesanan.</p>
                    <a href="{{ route('bookstore.index') }}" class="inline-block px-6 py-2.5 bg-[#DB8B00] text-white font-bold rounded-full text-xs uppercase tracking-wider">
                        Mulai Belanja
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
