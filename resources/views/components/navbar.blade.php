@php
    $cartCount = app(\App\Services\CartService::class)->getCount();
    $mainLinks = [
        ['name' => 'Home', 'href' => route('home'), 'active' => request()->routeIs('home'), 'highlight' => false],
        ['name' => 'Layanan', 'href' => route('home') . '#layanan', 'active' => false, 'highlight' => false],
        ['name' => 'Publishing', 'href' => route('home') . '#publishing', 'active' => false, 'highlight' => false],
        ['name' => 'WIM Bookstore', 'href' => route('bookstore.index'), 'active' => request()->routeIs('bookstore.*'), 'highlight' => true],
        ['name' => 'Blog', 'href' => route('blog.index'), 'active' => request()->routeIs('blog.*'), 'highlight' => false],
    ];
@endphp

<div x-data="{ mobileOpen: false, cartCount: {{ $cartCount }} }"
     @cart-updated.window="cartCount = $event.detail.count">
    <header class="fixed top-0 left-0 right-0 z-40 bg-[#F3F4F7]/95 backdrop-blur-md border-b border-[#C9CEDF]">
        {{-- Top Architectural Micro-Meta Bar --}}
        <div class="hidden md:block border-b border-[#D8DCEB] bg-[#ECEEF5]/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-7 flex items-center justify-between text-[10px] font-semibold tracking-[0.18em] uppercase text-[#636778]">
                <span>Workshop Indonesia Maju</span>
                <span>Penerbit Independen &bull; WIM Bookstore</span>
                <span>{{ date('Y') }} Edition</span>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-3">
            {{-- LEFT: Compact WIM Lockup with Full Name Underneath --}}
            <div class="flex items-center min-w-0 shrink-0">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 group">
                    <div class="size-9 bg-white border border-[#C9CEDF] p-1 flex items-center justify-center shrink-0 group-hover:border-[#2B3288] transition-colors">
                        <img src="{{ asset('wim.png') }}" alt="Logo WIM" class="w-full h-full object-contain">
                    </div>
                    <div class="leading-none">
                        <span class="block text-[#2B3288] font-sans font-extrabold text-base sm:text-lg tracking-[0.08em] uppercase whitespace-nowrap">
                            WIM
                        </span>
                        <span class="block text-[8.5px] sm:text-[9.5px] font-bold tracking-[0.1em] uppercase text-[#636778] mt-0.5 whitespace-nowrap">
                            Workshop Indonesia Maju
                        </span>
                    </div>
                </a>
            </div>

            {{-- CENTER: Architectural Single-Line Navigation --}}
            <nav class="hidden lg:flex items-center border border-[#C9CEDF] bg-white/95 divide-x divide-[#E3E7F3] shrink-0">
                @foreach ($mainLinks as $link)
                    <a href="{{ $link['href'] }}"
                       class="relative lg:px-3 xl:px-4 h-10 inline-flex items-center text-[11px] xl:text-xs uppercase tracking-[0.08em] whitespace-nowrap transition-colors duration-200 {{ $link['active'] ? 'bg-[#2B3288] text-white font-bold' : ($link['highlight'] ? 'text-[#2B3288] font-bold hover:bg-[#2B3288]/5' : 'text-[#636778] font-semibold hover:text-[#2B3288] hover:bg-[#F3F4F7]') }}">
                        {{ $link['name'] }}
                    </a>
                @endforeach

                <a href="{{ route('search') }}" title="Cari Buku & Artikel"
                   class="h-10 w-10 inline-flex items-center justify-center whitespace-nowrap transition-colors {{ request()->routeIs('search') ? 'bg-[#2B3288] text-white' : 'text-[#636778] hover:bg-[#F3F4F7] hover:text-[#2B3288]' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <span class="sr-only">Cari</span>
                </a>

                <button type="button" @click="$dispatch('open-cart-drawer')" title="Keranjang Belanja"
                        class="relative text-[#636778] hover:bg-[#F3F4F7] hover:text-[#2B3288] h-10 w-11 inline-flex items-center justify-center whitespace-nowrap transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    <span x-show="cartCount > 0" x-text="cartCount" x-cloak
                          class="absolute top-1 right-1 bg-[#2B3288] text-white text-[9px] font-bold min-w-[17px] h-[17px] px-1 flex items-center justify-center"></span>
                    <span class="sr-only">Keranjang</span>
                </button>
            </nav>

            {{-- RIGHT: Auth & Primary CTA (All nowrap) --}}
            <div class="flex items-center justify-end gap-2 shrink-0">
                <div class="hidden lg:flex items-center gap-1.5">
                    @auth
                        <a href="{{ auth()->user()->getDashboardUrl() }}"
                           class="px-3 h-10 border border-[#C9CEDF] bg-white text-[11px] xl:text-xs uppercase tracking-[0.06em] text-[#2B3288] hover:bg-[#2B3288] hover:text-white font-bold inline-flex items-center gap-2 whitespace-nowrap transition-colors max-w-[165px]">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <span class="truncate">{{ auth()->user()->name }}</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-3 h-10 inline-flex items-center text-[11px] xl:text-xs uppercase tracking-[0.08em] text-[#636778] hover:text-[#2B3288] font-semibold whitespace-nowrap transition-colors">
                            Log in
                        </a>
                        <a href="{{ route('register') }}" class="px-3 h-10 inline-flex items-center border border-[#C9CEDF] bg-white text-[11px] xl:text-xs uppercase tracking-[0.08em] text-[#111218] hover:border-[#2B3288] hover:text-[#2B3288] font-semibold whitespace-nowrap transition-colors">
                            Sign up
                        </a>
                    @endauth

                    <a href="{{ route('home') }}#ajukan-naskah"
                       class="inline-flex items-center h-10 px-4 xl:px-5 text-[11px] xl:text-xs font-bold uppercase tracking-[0.1em] whitespace-nowrap transition-colors bg-[#2B3288] hover:bg-[#1F256D] text-white">
                        Terbitkan Buku
                    </a>
                </div>

                {{-- Mobile Controls --}}
                <div class="lg:hidden flex items-center gap-1.5">
                    <a href="{{ route('search') }}" aria-label="Cari Buku dan Artikel"
                       class="h-9 w-9 inline-flex items-center justify-center border transition-colors {{ request()->routeIs('search') ? 'bg-[#2B3288] text-white border-[#2B3288]' : 'text-[#111218] bg-white border-[#C9CEDF]' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </a>
                    <button type="button" @click="$dispatch('open-cart-drawer')" aria-label="Buka Keranjang Belanja"
                            class="relative text-[#111218] bg-white border border-[#C9CEDF] h-9 w-9 inline-flex items-center justify-center cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                        <span x-show="cartCount > 0" x-text="cartCount" x-cloak class="absolute -top-1 -right-1 bg-[#2B3288] text-white text-[9px] font-bold w-4 h-4 flex items-center justify-center"></span>
                    </button>
                    <button type="button" @click="mobileOpen = true" aria-label="Buka Menu"
                            class="text-[#111218] bg-white border border-[#C9CEDF] h-9 w-9 inline-flex items-center justify-center cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    {{-- Mobile Fullscreen Drawer (Placed outside backdrop-blur header so fixed inset-0 spans full 100vh viewport) --}}
    <div x-show="mobileOpen"
         x-cloak
         x-transition.opacity
         @keydown.escape.window="mobileOpen = false"
         class="fixed inset-0 z-[60] bg-[#14194A] text-white flex flex-col lg:hidden">
        <div class="flex justify-between items-center border-b border-white/15 h-16 px-5 shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="size-8 bg-white p-1 flex items-center justify-center">
                    <img src="{{ asset('wim.png') }}" alt="Logo WIM" class="w-full h-full object-contain">
                </div>
                <div class="leading-none">
                    <span class="block text-sm font-extrabold tracking-[0.12em] uppercase text-white">WIM</span>
                    <span class="block text-[9px] uppercase tracking-[0.14em] text-white/60 mt-0.5">Workshop Indonesia Maju</span>
                </div>
            </div>
            <button type="button" @click="mobileOpen = false" aria-label="Tutup Menu"
                    class="text-white/80 hover:text-white border-l border-white/15 pl-5 h-full flex items-center cursor-pointer">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M18 6L6 18M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 flex flex-col overflow-y-auto">
            <div class="flex flex-col border-b border-white/15">
                @foreach ($mainLinks as $idx => $link)
                    <a href="{{ $link['href'] }}" @click="mobileOpen = false"
                       class="flex items-center justify-between text-2xl sm:text-3xl font-serif px-6 py-4 border-b border-white/15 transition-colors hover:bg-white/5 {{ $link['active'] ? 'bg-[#2B3288] text-white italic' : 'text-white' }}">
                        <span>{{ $link['name'] }}</span>
                        <span class="font-sans text-xs tracking-widest text-white/45">{{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    </a>
                @endforeach
                <a href="{{ route('search') }}" @click="mobileOpen = false"
                   class="flex items-center justify-between text-2xl sm:text-3xl font-serif px-6 py-4 transition-colors hover:bg-white/5 {{ request()->routeIs('search') ? 'bg-[#2B3288] text-white italic' : 'text-white' }}">
                    <span>Pencarian</span>
                    <span class="font-sans text-xs tracking-widest text-white/45">06</span>
                </a>
            </div>

            <div class="flex flex-col gap-3.5 px-6 py-7 mt-auto">
                <a href="{{ route('home') }}#ajukan-naskah" @click="mobileOpen = false"
                   class="w-full py-3.5 text-center bg-white text-[#2B3288] text-xs font-bold uppercase tracking-[0.15em]">
                    Terbitkan Buku &rarr;
                </a>
                <a href="{{ route('cart.index') }}" @click="mobileOpen = false"
                   class="py-2 text-sm font-medium text-white/90 hover:text-white flex items-center justify-between border-b border-white/10">
                    <span>Keranjang Belanja</span>
                    <span class="font-bold" x-text="cartCount + ' item'"></span>
                </a>
                @auth
                    <a href="{{ auth()->user()->getDashboardUrl() }}" @click="mobileOpen = false"
                       class="py-2 text-sm font-bold text-[#CBD2EB] hover:text-white">
                        Dashboard ({{ auth()->user()->name }}) &rarr;
                    </a>
                @else
                    <div class="grid grid-cols-2 gap-3 pt-1">
                        <a href="{{ route('login') }}" @click="mobileOpen = false"
                           class="py-2.5 text-center border border-white/25 text-xs font-bold uppercase tracking-wider text-white hover:bg-white/10">Log in</a>
                        <a href="{{ route('register') }}" @click="mobileOpen = false"
                           class="py-2.5 text-center bg-[#2B3288] border border-white/25 text-xs font-bold uppercase tracking-wider text-white hover:bg-white/15">Sign up</a>
                    </div>
                @endauth
            </div>
        </nav>
    </div>
</div>
