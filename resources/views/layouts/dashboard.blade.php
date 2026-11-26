@extends('layouts.app')

@section('content')
<div class="bg-[#F3F4F7] min-h-screen pt-28 md:pt-32 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Sidebar Navigasi Pelanggan --}}
            <aside class="w-full lg:w-64 shrink-0">
                <div class="bg-white border border-[#C9CEDF] p-5 sticky top-28 space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-[#E3E7F3]">
                        <div class="size-11 rounded-full bg-[#2B3288] text-white font-bold flex items-center justify-center text-lg shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-sm text-[#111218] truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-[#636778] truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    <nav class="flex flex-col gap-1 text-sm">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-[#2B3288] text-white font-semibold' : 'text-[#636778] hover:bg-[#F3F4F7] hover:text-[#2B3288]' }}">
                            <span>Ringkasan</span>
                        </a>
                        <a href="{{ route('dashboard.orders') }}" class="flex items-center gap-3 px-3.5 py-2.5 font-medium transition-colors {{ request()->routeIs('dashboard.orders*') ? 'bg-[#2B3288] text-white font-semibold' : 'text-[#636778] hover:bg-[#F3F4F7] hover:text-[#2B3288]' }}">
                            <span>Pesanan Saya</span>
                        </a>
                        <a href="{{ route('dashboard.wishlist') }}" class="flex items-center gap-3 px-3.5 py-2.5 font-medium transition-colors {{ request()->routeIs('dashboard.wishlist') ? 'bg-[#2B3288] text-white font-semibold' : 'text-[#636778] hover:bg-[#F3F4F7] hover:text-[#2B3288]' }}">
                            <span>Wishlist / Disimpan</span>
                        </a>
                        <a href="{{ route('dashboard.profile') }}" class="flex items-center gap-3 px-3.5 py-2.5 font-medium transition-colors {{ request()->routeIs('dashboard.profile') ? 'bg-[#2B3288] text-white font-semibold' : 'text-[#636778] hover:bg-[#F3F4F7] hover:text-[#2B3288]' }}">
                            <span>Profil & Alamat</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="pt-3 mt-2 border-t border-[#E3E7F3]">
                            @csrf
                            <button type="submit" class="w-full text-left px-3.5 py-2.5 text-red-600 font-semibold hover:bg-red-50 transition-colors cursor-pointer">
                                Keluar
                            </button>
                        </form>
                    </nav>
                </div>
            </aside>

            {{-- Konten Utama --}}
            <div class="flex-1 min-w-0">
                @yield('dashboard_content')
            </div>
        </div>
    </div>
</div>
@endsection
