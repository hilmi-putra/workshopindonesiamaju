<!DOCTYPE html>
<html lang="id" class="h-full antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel | WIM')</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,400..700;1,400..700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body x-data="{
          sidebarOpen: (() => { try { return localStorage.getItem('wim_admin_sidebar') !== '0'; } catch (e) { return true; } })(),
          mobileMenuOpen: false,
          profileOpen: false
      }"
      x-init="$watch('sidebarOpen', val => { try { localStorage.setItem('wim_admin_sidebar', val ? '1' : '0'); } catch (e) {} })"
      class="admin-theme h-screen w-full flex bg-[#F4F6FB] text-[#171512] overflow-hidden font-sans">

    {{-- Sidebar Desktop --}}
    <aside :class="sidebarOpen ? 'lg:w-64' : 'lg:w-20'"
           class="hidden lg:flex flex-col fixed inset-y-0 start-0 z-50 bg-[#F4F6FB] border-r border-[#E3E7F3]/80 transition-all duration-300">
        <div :class="sidebarOpen ? 'px-4 justify-between' : 'px-2 justify-center'"
             class="h-16 flex items-center shrink-0 transition-all">
            <a x-show="sidebarOpen"
               href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 overflow-hidden min-w-0">
                <div class="size-10 bg-white rounded-xl flex items-center justify-center shadow-xs border border-[#E3E7F3] p-1.5 shrink-0">
                    <img src="{{ asset('wim.png') }}" alt="Logo WIM" class="size-full object-contain">
                </div>
                <span class="text-[#2B3288] text-xl tracking-tight font-extrabold whitespace-nowrap">
                    WIM
                </span>
            </a>

            <button x-show="!sidebarOpen"
                    x-cloak
                    @click="sidebarOpen = true"
                    type="button"
                    title="Perbesar Sidebar"
                    aria-label="Perbesar Sidebar"
                    class="size-10 bg-white rounded-xl flex items-center justify-center shadow-xs border border-[#E3E7F3] hover:border-[#2B3288]/40 hover:ring-2 hover:ring-[#2B3288]/10 p-1.5 shrink-0 transition-all cursor-pointer">
                <img src="{{ asset('wim.png') }}" alt="Logo WIM" class="size-full object-contain">
            </button>

            <button x-show="sidebarOpen"
                    @click="sidebarOpen = false"
                    type="button"
                    title="Kecilkan Sidebar"
                    aria-label="Kecilkan Sidebar"
                    class="p-1.5 rounded-lg text-gray-400 hover:bg-[#2B3288]/10 hover:text-[#2B3288] transition-colors cursor-pointer shrink-0">
                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>

        <nav class="p-3 flex-1 overflow-y-auto no-scrollbar space-y-5">
            @include('admin.partials.sidebar-links')
        </nav>
    </aside>

    {{-- Mobile Drawer Sidebar --}}
    <div x-show="mobileMenuOpen" x-cloak class="fixed inset-0 z-50 lg:hidden flex">
        <div @click="mobileMenuOpen = false" class="fixed inset-0 bg-[#141738]/50 backdrop-blur-xs"></div>
        <aside x-data="{ sidebarOpen: true }" class="relative w-72 max-w-[85vw] bg-[#F4F6FB] h-full flex flex-col shadow-xl z-10">
            <div class="h-16 flex items-center justify-between px-4 border-b border-[#E3E7F3]">
                <div class="flex items-center gap-2.5 min-w-0">
                    <img src="{{ asset('wim.png') }}" alt="Logo WIM" class="size-7 object-contain shrink-0">
                    <span class="font-extrabold text-lg text-[#2B3288] tracking-tight">WIM Admin</span>
                </div>
                <button @click="mobileMenuOpen = false" class="p-2 text-gray-500 hover:text-[#2B3288] cursor-pointer">&times;</button>
            </div>
            <nav class="p-3 flex-1 overflow-y-auto space-y-5">
                @include('admin.partials.sidebar-links')
            </nav>
        </aside>
    </div>

    {{-- Main Content Shell --}}
    <div :class="sidebarOpen ? 'lg:ps-64' : 'lg:ps-20'" class="flex-1 min-w-0 w-full flex flex-col h-screen transition-all duration-300">
        {{-- Top Navbar --}}
        <header class="h-16 flex items-center justify-between px-4 sm:px-8 bg-[#F4F6FB]/85 backdrop-blur-md border-b border-[#E3E7F3] shrink-0">
            <div class="flex items-center gap-3">
                <button @click="mobileMenuOpen = true" type="button" class="lg:hidden p-2 rounded-lg text-gray-600 hover:bg-[#2B3288]/10 hover:text-[#2B3288] cursor-pointer">
                    <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="text-sm text-gray-500 flex items-center gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-[#2B3288] font-medium transition-colors">Dashboard</a>
                    @hasSection('breadcrumb')
                        <span class="text-gray-300">/</span>
                        <span class="font-semibold text-[#2B3288]">@yield('breadcrumb')</span>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 text-xs font-semibold text-gray-600 hover:text-[#2B3288] px-3.5 py-1.5 rounded-full bg-white border border-[#D9DFEE] hover:border-[#2B3288]/40 transition-colors">
                    Lihat Website &rarr;
                </a>

                <div class="relative">
                    <button @click="profileOpen = !profileOpen" type="button" class="flex items-center gap-2.5 focus:outline-none cursor-pointer">
                        <div class="hidden md:block text-right">
                            <p class="text-sm font-semibold text-gray-900 leading-tight">{{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-gray-400">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="size-9 rounded-full bg-[#2B3288] text-white font-bold flex items-center justify-center shadow-xs">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </button>

                    <div x-show="profileOpen" @click.away="profileOpen = false" x-cloak
                         class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-[#E3E7F3] py-2 z-50">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-xs font-bold uppercase text-[#2B3288]">Admin WIM</p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#F4F6FB] hover:text-[#2B3288]">Dashboard</a>
                        <a href="{{ route('admin.profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#F4F6FB] hover:text-[#2B3288]">Profil Saya</a>
                        <form method="POST"
                              action="{{ route('logout') }}"
                              data-confirm
                              data-confirm-title="Keluar dari Admin WIM?"
                              data-confirm-message="Sesi Anda akan diakhiri dan Anda perlu login kembali untuk mengakses dashboard admin."
                              data-confirm-button="Ya, Keluar"
                              data-confirm-variant="primary">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm font-bold text-red-600 hover:bg-red-50 cursor-pointer">Keluar</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Inner White Rounded Canvas --}}
        <main class="flex-1 min-h-0 p-3 sm:p-4 overflow-hidden flex flex-col">
            <div class="flex-1 bg-white border border-[#E3E7F3] rounded-[2rem] p-5 sm:p-8 shadow-xs overflow-y-auto no-scrollbar">
                <x-flash-message inline />
                @hasSection('page_title')
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-7 pb-5 border-b border-[#E3E7F3]">
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-[#171512]">@yield('page_title')</h1>
                            @hasSection('page_description')
                                <p class="text-sm text-gray-500 mt-1">@yield('page_description')</p>
                            @endif
                        </div>
                        @yield('page_actions')
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <x-confirm-modal />

    @livewireScripts
    @stack('scripts')
</body>
</html>
