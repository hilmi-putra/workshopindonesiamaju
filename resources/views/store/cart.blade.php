@extends('layouts.app')

@section('title', 'Keranjang Belanja | WIM Bookstore')

@section('content')
<div class="flex flex-col bg-[#F3F4F7] text-[#111218] min-h-screen pb-24 pt-20 sm:pt-28 md:pt-36">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="flex items-center justify-between border-t border-[#111218]/40 pt-3 pb-6 text-xs font-bold uppercase tracking-[0.18em]">
            <span>WIM Bookstore // Checkout</span>
            <span class="text-[#2B3288]">Pemesanan WhatsApp</span>
        </div>

        <h1 class="mb-10 font-serif text-4xl sm:text-6xl tracking-[-0.03em] text-[#111218]">
            Keranjang <em class="italic font-normal text-[#2B3288]">Belanja</em>
        </h1>

        <livewire:cart-page />
    </div>
</div>
@endsection
