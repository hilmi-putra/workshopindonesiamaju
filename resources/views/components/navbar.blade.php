@php
    $cartCount = app(\App\Services\CartService::class)->getCount();
    $mainLinks = [
        ['name' => 'Home', 'href' => route('home'), 'active' => request()->routeIs('home'), 'highlight' => false],
        ['name' => 'Layanan', 'href' => route('home') . '#layanan', 'active' => false, 'highlight' => false],
        ['name' => 'Publishing', 'href' => route('home') . '#publishing', 'active' => false, 'highlight' => false],
        ['name' => 'Mega Bookstore', 'href' => route('bookstore.index'), 'active' => request()->routeIs('bookstore.*'), 'highlight' => true],
        ['name' => 'Blog', 'href' => route('blog.index'), 'active' => request()->routeIs('blog.*'), 'highlight' => false],
    ];
@endphp

<header x-data="{ mobileOpen: false, cartCount: {{ $cartCount }} }"
        @cart-updated.window="cartCount = $event.detail.count"
        class="fixed top-4 left-0 right-0 z-50 px-4 md:px-6">
    <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
        {{-- LEFT: Logo --}}
        <div class="flex-1">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
                <img src="https://ik.imagekit.io/yqhp1cmbp/logo_megapress.svg" alt="Mega Press Logo" class="w-8 h-8">
                <span class="text-[#DB8B00] font-sans font-bold text-xl tracking-tight">Mega Press.</span>
            </a>
        </div>

        {{-- CENTER: Glassmorphic Pill Navigation --}}
        <nav class="hidden lg:flex items-center rounded-full border border-white/50 bg-white/75 shadow-sm backdrop-blur-md px-2 py-1">
            @foreach ($mainLinks as $link)
                <a href="{{ $link['href'] }}"
                   class="relative px-4 py-2 rounded-full text-sm transition-colors duration-200 {{ $link['active'] ? 'bg-[#DB8B00]/10 text-[#DB8B00] font-semibold' : ($link['highlight'] ? 'text-[#DB8B00] font-medium hover:bg-black/5' : 'text-[#6B6860] hover:text-[#171512] hover:bg-black/5') }}">
                    {{ $link['name'] }}
                </a>
            @endforeach

            <div class="w-px h-6 bg-[#DDD8C8]/70 mx-2"></div>

            <a href="{{ route('search') }}" title="Cari Buku & Artikel"
               class="rounded-full text-[#6B6860] hover:bg-black/5 hover:text-[#171512] h-9 w-9 inline-flex items-center justify-center transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <span class="sr-only">Cari</span>
            </a>

            <button type="button" @click="$dispatch('open-cart-drawer')" title="Keranjang Belanja"
                    class="rounded-full text-[#6B6860] hover:bg-black/5 hover:text-[#171512] relative h-9 w-9 inline-flex items-center justify-center transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                <span x-show="cartCount > 0" x-text="cartCount" x-cloak
                      class="absolute -top-1 -right-1 bg-[#6E0000] text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center border-2 border-white"></span>
                <span class="sr-only">Keranjang</span>
            </button>
        </nav>

        {{-- RIGHT: Auth & CTA --}}
        <div class="flex-1 flex items-center justify-end gap-2">
            <div class="hidden md:flex items-center gap-1 mr-1">
                @auth
                    <a href="{{ auth()->user()->getDashboardUrl() }}"
                       class="rounded-full px-4 py-2 text-sm text-[#DB8B00] hover:bg-[#DB8B00]/10 font-semibold inline-flex items-center gap-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span>{{ \Illuminate\Support\Str::limit(auth()->user()->name, 16) }}</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="rounded-full px-4 py-2 text-sm text-[#6B6860] hover:bg-black/5 hover:text-[#171512] font-medium transition-colors">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="rounded-full px-4 py-2 text-sm text-[#6B6860] hover:bg-black/5 hover:text-[#171512] font-medium transition-colors">
                        Sign up
                    </a>
                @endauth
            </div>

            <div class="hidden lg:block">
                <a href="{{ route('home') }}#ajukan-naskah"
                   class="inline-flex items-center rounded-full px-6 py-2.5 text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-0.5 bg-[#DB8B00] hover:bg-[#c07a00] text-white">
                    Terbitkan Buku
                </a>
            </div>

            {{-- Mobile Controls --}}
            <div class="lg:hidden flex items-center gap-2">
                <a href="{{ route('search') }}" class="rounded-full text-[#6B6860] bg-white/70 backdrop-blur-md shadow-sm border border-white/40 h-9 w-9 inline-flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </a>
                <button type="button" @click="$dispatch('open-cart-drawer')" class="relative rounded-full text-[#6B6860] bg-white/70 backdrop-blur-md shadow-sm border border-white/40 h-9 w-9 inline-flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    <span x-show="cartCount > 0" x-text="cartCount" x-cloak class="absolute -top-1 -right-1 bg-[#6E0000] text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center"></span>
                </button>
                <button type="button" @click="mobileOpen = true" class="rounded-full text-[#6B6860] bg-white/70 backdrop-blur-md shadow-sm border border-white/40 h-9 w-9 inline-flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Fullscreen Forest Green Drawer (Matches Next.js Sheet) --}}
    <div x-show="mobileOpen" x-cloak x-transition.opacity
         class="fixed inset-0 z-50 bg-[#084c3c] flex flex-col lg:hidden">
        <div class="flex justify-between items-center border-b border-white/20 h-16 px-6">
            <span class="text-white/60 text-xs font-semibold tracking-widest uppercase">Menu</span>
            <button type="button" @click="mobileOpen = false" class="text-white/80 hover:text-white border-l border-white/20 pl-6 h-full flex items-center">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M18 6L6 18M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="flex flex-col w-full h-full overflow-y-auto">
            <div class="flex flex-col border-b border-white/20">
                @foreach ($mainLinks as $link)
                    <a href="{{ $link['href'] }}" @click="mobileOpen = false"
                       class="text-3xl font-serif px-6 py-5 border-b border-white/20 transition-colors hover:bg-white/5 {{ $link['active'] ? 'text-[#DB8B00]' : 'text-white' }}">
                        {{ $link['name'] }}
                    </a>
                @endforeach
            </div>

            <div class="flex flex-col gap-4 px-6 py-8 mt-auto mb-6">
                <a href="{{ route('cart.index') }}" @click="mobileOpen = false" class="text-sm font-medium text-white/90 hover:text-white">
                    Keranjang (<span x-text="cartCount"></span>)
                </a>
                @auth
                    <a href="{{ auth()->user()->getDashboardUrl() }}" @click="mobileOpen = false" class="text-sm font-medium text-[#DB8B00]">
                        Dashboard ({{ auth()->user()->name }})
                    </a>
                @else
                    <a href="{{ route('login') }}" @click="mobileOpen = false" class="text-sm font-medium text-white/80 hover:text-white">Log in</a>
                    <a href="{{ route('register') }}" @click="mobileOpen = false" class="text-sm font-medium text-white/80 hover:text-white">Sign up / Register</a>
                @endauth
            </div>
        </nav>
    </div>
</header>
