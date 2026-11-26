@extends('layouts.dashboard')

@section('title', 'Pesanan Saya | Workshop Indonesia Maju')

@section('dashboard_content')
<div class="bg-white rounded-2xl border border-[#DDD8C8] overflow-hidden">
    <div class="p-6 md:p-8 border-b border-gray-100">
        <h1 class="text-2xl font-black text-[#171512] uppercase tracking-tighter mb-1">Pesanan Saya</h1>
        <p class="text-sm text-gray-500">Daftar pesanan WhatsApp Anda beserta status prosesnya.</p>
    </div>

    <div class="p-6 md:p-8 space-y-6">
        @forelse($orders as $order)
            <div class="border border-gray-200 rounded-xl overflow-hidden bg-white">
                <div class="bg-gray-50 p-4 sm:p-5 border-b border-gray-200 flex flex-wrap justify-between items-center gap-4 text-sm">
                    <div class="flex flex-wrap gap-6">
                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kode Pesanan</span>
                            <span class="font-bold text-[#171512]">{{ $order->display_code }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tanggal</span>
                            <span class="font-medium text-gray-700">{{ $order->created_at?->format('d M Y') }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Buku</span>
                            <span class="font-bold text-[#DB8B00]">Rp {{ number_format($order->display_total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $order->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : ($order->status === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-[#DB8B00]/15 text-[#DB8B00]') }}">
                            {{ $order->status_label }}
                        </span>
                        <a href="{{ route('dashboard.orders.show', $order->display_code) }}"
                           class="px-4 py-2 rounded-lg border border-gray-200 text-xs font-bold uppercase tracking-wider hover:bg-gray-100">
                            Detail
                        </a>
                    </div>
                </div>

                <div class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <div class="p-4 sm:p-5 flex items-center gap-4">
                            <div class="w-14 h-20 bg-[#EBEBEB] rounded-sm shrink-0 overflow-hidden">
                                @if($item->book)
                                    <img src="{{ $item->book->cover_url }}" alt="{{ $item->display_title }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-[#171512] text-sm sm:text-base truncate">{{ $item->display_title }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $item->display_qty }} eks &times; Rp {{ number_format($item->display_price, 0, ',', '.') }}</p>
                            </div>
                            <div class="font-bold text-sm text-[#171512]">
                                Rp {{ number_format($item->line_total, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="py-16 text-center bg-gray-50/50 rounded-xl border border-dashed border-gray-200">
                <p class="text-gray-500 font-medium mb-4">Belum ada pesanan.</p>
                <a href="{{ route('bookstore.index') }}" class="inline-block px-7 py-3 bg-[#DB8B00] text-white rounded-full text-xs font-bold uppercase tracking-widest">
                    Mulai Belanja
                </a>
            </div>
        @endforelse

        <div class="pt-2">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
