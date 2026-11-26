@extends('layouts.admin')

@section('title', 'Pesanan WhatsApp | Admin Mega Press')
@section('breadcrumb', 'Pesanan')
@section('page_title', 'Manajemen Pesanan WhatsApp')
@section('page_description', 'Kelola pesanan masuk dari WhatsApp maupun pesanan manual toko.')

@section('page_actions')
    <a href="{{ route('admin.orders.create') }}" class="px-5 py-2.5 rounded-xl bg-[#DB8B00] hover:bg-[#b06f00] text-white text-xs font-bold uppercase tracking-wider">
        + Pesanan Manual
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 sm:grid-cols-5 gap-3 bg-[#FAF8F4] p-4 rounded-2xl border border-gray-100">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode, nama, atau HP..."
               class="sm:col-span-2 h-10 rounded-xl border border-gray-200 bg-white px-3.5 text-sm">
        <select name="status" class="h-10 rounded-xl border border-gray-200 bg-white px-3 text-sm">
            <option value="all">Semua Status</option>
            @foreach($statuses as $key => $label)
                <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="h-10 rounded-xl border border-gray-200 bg-white px-3 text-sm">
        <div class="flex gap-2">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="flex-1 h-10 rounded-xl border border-gray-200 bg-white px-3 text-sm">
            <button type="submit" class="px-4 h-10 rounded-xl bg-[#171512] text-white text-xs font-bold uppercase cursor-pointer">Filter</button>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs uppercase text-gray-400 border-b border-gray-200">
                <tr>
                    <th class="py-3 px-2">Kode</th>
                    <th class="py-3 px-2">Pelanggan</th>
                    <th class="py-3 px-2">Item</th>
                    <th class="py-3 px-2">Total</th>
                    <th class="py-3 px-2">Status</th>
                    <th class="py-3 px-2">Tanggal</th>
                    <th class="py-3 px-2 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/70">
                        <td class="py-3.5 px-2 font-bold text-[#171512]">{{ $order->display_code }}</td>
                        <td class="py-3.5 px-2">
                            <p class="font-semibold">{{ $order->customer_name }}</p>
                            <p class="text-xs text-gray-500">{{ $order->customer_phone }}</p>
                        </td>
                        <td class="py-3.5 px-2 text-xs text-gray-600">{{ $order->items->sum(fn ($i) => $i->display_qty) }} eks</td>
                        <td class="py-3.5 px-2 font-bold text-[#171512]">Rp {{ number_format($order->display_total, 0, ',', '.') }}</td>
                        <td class="py-3.5 px-2">
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold {{ $order->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : ($order->status === 'cancelled' ? 'bg-red-100 text-red-700' : ($order->status === 'confirmed' ? 'bg-blue-100 text-blue-700' : 'bg-[#F7F09E] text-amber-950')) }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="py-3.5 px-2 text-xs text-gray-500">{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                        <td class="py-3.5 px-2 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}" class="px-3 py-1.5 rounded-lg bg-[#FAF8F4] border border-gray-200 text-xs font-bold hover:border-[#DB8B00]">
                                Kelola
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-gray-400">Belum ada pesanan yang sesuai filter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $orders->links() }}</div>
</div>
@endsection
