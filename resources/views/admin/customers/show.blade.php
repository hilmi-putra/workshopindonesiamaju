@extends('layouts.admin')

@section('title', 'Detail Pelanggan | Admin Mega Press')
@section('breadcrumb', 'Pelanggan / Detail')
@section('page_title', $customer->name)
@section('page_description', $customer->email . ' · ' . ($customer->phone ?: '-'))

@section('content')
<div class="space-y-8">
    <div class="p-5 rounded-2xl bg-[#FAF8F4] border border-gray-100 text-sm">
        <p><strong>Alamat Default:</strong> {{ $customer->address ?: 'Belum diisi' }}</p>
        <p class="mt-1"><strong>Bergabung:</strong> {{ $customer->created_at?->format('d M Y') }}</p>
    </div>

    <div>
        <h2 class="font-bold text-base mb-3">Riwayat Pesanan ({{ $customer->orders->count() }})</h2>
        <div class="border border-gray-100 rounded-2xl divide-y divide-gray-100">
            @forelse($customer->orders as $order)
                <div class="p-4 flex justify-between items-center text-sm">
                    <div>
                        <a href="{{ route('admin.orders.show', $order) }}" class="font-bold text-[#DB8B00] hover:underline">{{ $order->display_code }}</a>
                        <p class="text-xs text-gray-500">{{ $order->created_at?->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold">Rp {{ number_format($order->display_total, 0, ',', '.') }}</p>
                        <span class="text-xs text-gray-500">{{ $order->status_label }}</span>
                    </div>
                </div>
            @empty
                <p class="p-6 text-center text-xs text-gray-400">Belum ada pesanan.</p>
            @endforelse
        </div>
    </div>

    <div>
        <h2 class="font-bold text-base mb-3">Wishlist ({{ $customer->wishlists->count() }})</h2>
        <div class="flex flex-wrap gap-3">
            @forelse($customer->wishlists as $wish)
                @if($wish->book)
                    <span class="px-4 py-2 rounded-xl bg-[#FAF8F4] border border-gray-200 text-xs font-semibold">{{ $wish->book->title }}</span>
                @endif
            @empty
                <p class="text-xs text-gray-400">Wishlist kosong.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
