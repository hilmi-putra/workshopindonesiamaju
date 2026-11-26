@extends('layouts.admin')

@section('title', 'Data Pelanggan | Admin Mega Press')
@section('breadcrumb', 'Pelanggan')
@section('page_title', 'Data Pelanggan')

@section('content')
<div class="space-y-5">
    <form method="GET" action="{{ route('admin.customers.index') }}" class="flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau HP..." class="flex-1 h-10 rounded-xl border border-gray-200 px-4 text-sm">
        <button type="submit" class="px-5 h-10 rounded-xl bg-[#171512] text-white text-xs font-bold uppercase cursor-pointer">Cari</button>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs uppercase text-gray-400 border-b border-gray-200">
                <tr>
                    <th class="py-3">Nama</th>
                    <th class="py-3">Kontak</th>
                    <th class="py-3">Pesanan</th>
                    <th class="py-3">Wishlist</th>
                    <th class="py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($customers as $customer)
                    <tr>
                        <td class="py-3 font-bold">{{ $customer->name }}</td>
                        <td class="py-3 text-xs text-gray-600">
                            {{ $customer->email }}<br>
                            <span>{{ $customer->phone ?: '-' }}</span>
                        </td>
                        <td class="py-3 font-semibold">{{ $customer->orders_count }}</td>
                        <td class="py-3 font-semibold">{{ $customer->wishlists_count }}</td>
                        <td class="py-3 text-right">
                            <a href="{{ route('admin.customers.show', $customer) }}" class="text-xs font-bold text-[#DB8B00] hover:underline">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-10 text-center text-gray-400">Belum ada pelanggan terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>{{ $customers->links() }}</div>
</div>
@endsection
