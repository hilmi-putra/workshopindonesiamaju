@extends('layouts.admin')

@section('title', 'Laporan Penjualan | Admin WIM')
@section('breadcrumb', 'Laporan')
@section('page_title', 'Laporan Penjualan & Buku Terlaris')

@section('page_actions')
    <div class="flex gap-2">
        <a href="{{ route('admin.reports.index', array_merge(request()->query(), ['export' => 'excel'])) }}"
           class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold uppercase tracking-wider">
            Ekspor Excel
        </a>
        <a href="{{ route('admin.reports.index', array_merge(request()->query(), ['export' => 'pdf'])) }}"
           class="px-4 py-2 rounded-xl bg-[#2B3288] hover:bg-[#20266E] text-white text-xs font-bold uppercase tracking-wider">
            Ekspor PDF
        </a>
    </div>
@endsection

@section('content')
<div class="space-y-8">
    <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 bg-[#F5F7FC] p-4 rounded-2xl border border-[#E3E7F3]">
        <input type="date" name="start_date" value="{{ $startDate }}" class="h-10 rounded-xl border border-[#D9DFEE] bg-white px-3 text-sm">
        <input type="date" name="end_date" value="{{ $endDate }}" class="h-10 rounded-xl border border-[#D9DFEE] bg-white px-3 text-sm">
        <select name="status" class="h-10 rounded-xl border border-[#D9DFEE] bg-white px-3 text-sm">
            <option value="all" @selected($status === 'all')>Semua Status</option>
            @foreach(\App\Models\Order::statuses() as $key => $label)
                <option value="{{ $key }}" @selected($status === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="h-10 rounded-xl bg-[#2B3288] hover:bg-[#20266E] text-white text-xs font-bold uppercase cursor-pointer transition-colors">Tampilkan Laporan</button>
    </form>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-5 rounded-2xl border border-[#E3E7F3] bg-[#F5F7FC]">
            <p class="text-xs font-bold uppercase text-gray-400">Total Pesanan</p>
            <p class="text-2xl font-black mt-1">{{ $summary['total_orders'] }}</p>
        </div>
        <div class="p-5 rounded-2xl border border-[#E3E7F3] bg-[#F5F7FC]">
            <p class="text-xs font-bold uppercase text-gray-400">Lunas / Selesai</p>
            <p class="text-2xl font-black text-emerald-700 mt-1">{{ $summary['paid_orders'] }}</p>
        </div>
        <div class="p-5 rounded-2xl border border-[#E3E7F3] bg-[#F5F7FC]">
            <p class="text-xs font-bold uppercase text-gray-400">Total Eksemplar</p>
            <p class="text-2xl font-black mt-1">{{ $summary['total_items'] }}</p>
        </div>
        <div class="p-5 rounded-2xl border border-[#E3E7F3] bg-[#F5F7FC]">
            <p class="text-xs font-bold uppercase text-gray-400">Omzet Lunas</p>
            <p class="text-xl font-black text-[#2B3288] mt-1">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-5 border border-[#E3E7F3] rounded-2xl p-6">
            <h2 class="font-bold text-base mb-4">Top Buku Terlaris</h2>
            <div class="divide-y divide-gray-100">
                @forelse($bestSellers as $idx => $item)
                    <div class="py-3 flex justify-between items-center text-sm">
                        <div>
                            <p class="font-bold">{{ $idx + 1 }}. {{ $item->book?->title ?: $item->book_title }}</p>
                            <p class="text-xs text-gray-400">Rp {{ number_format((float)$item->total_revenue, 0, ',', '.') }}</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-[#2B3288]/10 text-[#2B3288] font-bold text-xs">{{ $item->total_sold }} eks</span>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 py-6 text-center">Belum ada data penjualan di periode ini.</p>
                @endforelse
            </div>
        </div>

        <div class="lg:col-span-7 border border-gray-100 rounded-2xl p-6 overflow-x-auto">
            <h2 class="font-bold text-base mb-4">Daftar Transaksi</h2>
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase text-gray-400 border-b border-gray-100">
                    <tr>
                        <th class="py-2">Kode</th>
                        <th class="py-2">Pelanggan</th>
                        <th class="py-2">Total</th>
                        <th class="py-2">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($orders as $order)
                        <tr>
                            <td class="py-2.5 font-bold">{{ $order->display_code }}</td>
                            <td class="py-2.5">{{ $order->customer_name }}</td>
                            <td class="py-2.5">Rp {{ number_format($order->display_total, 0, ',', '.') }}</td>
                            <td class="py-2.5 text-xs">{{ $order->status_label }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">{{ $orders->links() }}</div>
        </div>
    </div>
</div>
@endsection
