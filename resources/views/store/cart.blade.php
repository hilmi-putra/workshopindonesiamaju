@extends('layouts.app')

@section('title', 'Keranjang Belanja | Mega Bookstore')

@section('content')
<div class="flex flex-col bg-[#FAF8F4] min-h-screen pb-24 pt-28 md:pt-36">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <h1 class="mb-10 font-sans font-black text-3xl sm:text-5xl uppercase tracking-tighter text-[#171512]">
            Keranjang Belanja
        </h1>

        <livewire:cart-page />
    </div>
</div>
@endsection
