@extends('layouts.admin')

@section('title', 'Admin Dashboard | WIM')
@section('page_title', 'Dashboard Admin WIM')
@section('page_description', 'Ringkasan pesanan WhatsApp, pendapatan, dan stok buku WIM.')

@section('content')
<div class="space-y-8">
    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-5 rounded-2xl border border-[#E3E7F3] bg-[#F5F7FC]">
            <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Pesanan Pending</p>
            <p class="text-3xl font-black text-[#2B3288] mt-2">{{ $pendingOrdersCount }}</p>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="text-xs text-[#2B3288] font-semibold mt-2 inline-block hover:underline">Tindak lanjuti &rarr;</a>
        </div>
        <div class="p-5 rounded-2xl border border-[#E3E7F3] bg-[#F5F7FC]">
            <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Menunggu Bayar (Confirmed)</p>
            <p class="text-3xl font-black text-blue-700 mt-2">{{ $confirmedOrdersCount }}</p>
        </div>
        <div class="p-5 rounded-2xl border border-[#E3E7F3] bg-[#F5F7FC]">
            <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Pendapatan (Paid/Selesai)</p>
            <p class="text-2xl font-black text-emerald-700 mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="p-5 rounded-2xl border border-[#E3E7F3] bg-[#F5F7FC]">
            <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Naskah Baru</p>
            <p class="text-3xl font-black text-[#2B3288] mt-2">{{ $pendingSubmissionsCount }}</p>
            <a href="{{ route('admin.submissions.index') }}" class="text-xs text-[#2B3288] font-semibold mt-2 inline-block hover:underline">Lihat naskah &rarr;</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Simple 7-Day Revenue Bar Chart --}}
        <div class="lg:col-span-7 border border-[#E3E7F3] rounded-2xl p-6 bg-white">
            <h2 class="font-bold text-base text-[#171512] mb-6">Grafik Pesanan 7 Hari Terakhir</h2>
            <div class="flex items-end gap-3 h-48 pt-4">
                @foreach($days as $day)
                    @php $heightPct = max(8, (int) round(($day['revenue'] / $maxChartVal) * 100)); @endphp
                    <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end">
                        <span class="text-[10px] font-bold text-gray-500">{{ $day['count'] }}</span>
                        <div class="w-full max-w-[40px] bg-[#2B3288]/20 hover:bg-[#2B3288]/35 border border-[#2B3288]/40 rounded-t-lg transition-all"
                             style="height: {{ $heightPct }}%"
                             title="Rp {{ number_format($day['revenue'], 0, ',', '.') }}">
                        </div>
                        <span class="text-[10px] font-semibold text-gray-500">{{ $day['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Low Stock Books --}}
        <div class="lg:col-span-5 border border-[#E3E7F3] rounded-2xl p-6 bg-white">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-base text-[#171512]">Stok Menipis (&le; 5)</h2>
                <a href="{{ route('admin.stock.index') }}" class="text-xs font-bold text-[#2B3288] hover:underline">Kelola Stok</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($lowStockBooks as $book)
                    <div class="py-2.5 flex items-center justify-between gap-3 text-sm">
                        <div class="min-w-0">
                            <p class="font-semibold text-[#171512] truncate">{{ $book->title }}</p>
                            <p class="text-xs text-gray-400">{{ $book->author?->name }}</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $book->stock <= 0 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-800' }}">
                            Sisa {{ $book->stock }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 py-6 text-center">Semua stok buku aman.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="border border-[#E3E7F3] rounded-2xl p-6 bg-white">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-base text-[#171512]">Pesanan Terbaru</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-[#2B3288] hover:underline">Semua Pesanan &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase text-gray-400 border-b border-gray-100">
                    <tr>
                        <th class="py-3">Kode</th>
                        <th class="py-3">Pelanggan</th>
                        <th class="py-3">Total</th>
                        <th class="py-3">Status</th>
                        <th class="py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($recentOrders as $order)
                        <tr>
                            <td class="py-3 font-bold">{{ $order->display_code }}</td>
                            <td class="py-3">{{ $order->customer_name }} <span class="text-xs text-gray-400">({{ $order->customer_phone }})</span></td>
                            <td class="py-3 font-semibold">Rp {{ number_format($order->display_total, 0, ',', '.') }}</td>
                            <td class="py-3">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#2B3288]/10 text-[#2B3288]">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="py-3 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-xs font-bold text-[#2B3288] hover:underline">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
