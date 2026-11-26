<!DOCTYPE html>
<html lang="id" class="h-full antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel | Mega Press')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,400..700;1,400..700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body x-data="{ sidebarOpen: true, mobileMenuOpen: false, profileOpen: false }"
      class="h-screen w-full flex bg-[#F7F8F2] text-[#171512] overflow-hidden font-sans">

    {{-- Sidebar Desktop & Mobile --}}
    <aside :class="sidebarOpen ? 'lg:w-64' : 'lg:w-20'"
           class="hidden lg:flex flex-col fixed inset-y-0 start-0 z-50 bg-[#F7F8F2] transition-all duration-300">
        <div class="h-16 flex items-center px-4 shrink-0 justify-between">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 overflow-hidden">
                <div class="size-10 bg-white rounded-xl flex items-center justify-center shadow-sm p-1.5 shrink-0">
                    <img src="https://ik.imagekit.io/yqhp1cmbp/logo_megapress.svg" alt="Mega Press" class="size-full object-contain">
                </div>
                <span x-show="sidebarOpen" x-transition.opacity class="text-gray-900 text-xl tracking-tight font-bold whitespace-nowrap">
                    MegaPress
                </span>
            </a>
            <button @click="sidebarOpen = !sidebarOpen" type="button" class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-200/60 hover:text-gray-700">
                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>

        <nav class="p-3 flex-1 overflow-y-auto no-scrollbar space-y-5">
            @include('admin.partials.sidebar-links')
        </nav>
    </aside>

    {{-- Mobile Drawer Sidebar --}}
    <div x-show="mobileMenuOpen" x-cloak class="fixed inset-0 z-50 lg:hidden flex">
        <div @click="mobileMenuOpen = false" class="fixed inset-0 bg-black/40 backdrop-blur-xs"></div>
        <aside class="relative w-72 max-w-[85vw] bg-[#F7F8F2] h-full flex flex-col shadow-xl z-10">
            <div class="h-16 flex items-center justify-between px-4 border-b border-gray-200/70">
                <span class="font-bold text-lg text-[#171512]">MegaPress Admin</span>
                <button @click="mobileMenuOpen = false" class="p-2 text-gray-500">&times;</button>
            </div>
            <nav class="p-3 flex-1 overflow-y-auto space-y-5">
                @include('admin.partials.sidebar-links')
            </nav>
        </aside>
    </div>

    {{-- Main Content Shell --}}
    <div :class="sidebarOpen ? 'lg:ps-64' : 'lg:ps-20'" class="flex-1 min-w-0 w-full flex flex-col h-screen transition-all duration-300">
        {{-- Top Navbar --}}
        <header class="h-16 flex items-center justify-between px-4 sm:px-8 bg-[#F7F8F2]/80 backdrop-blur-md border-b border-gray-200/60 shrink-0">
            <div class="flex items-center gap-3">
                <button @click="mobileMenuOpen = true" type="button" class="lg:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-200/60">
                    <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="text-sm text-gray-500 flex items-center gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-[#171512]">Dashboard</a>
                    @hasSection('breadcrumb')
                        <span>/</span>
                        <span class="font-semibold text-[#171512]">@yield('breadcrumb')</span>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 text-xs font-semibold text-gray-600 hover:text-[#DB8B00] px-3 py-1.5 rounded-full bg-white border border-gray-200">
                    Lihat Website &rarr;
                </a>

                <div class="relative">
                    <button @click="profileOpen = !profileOpen" type="button" class="flex items-center gap-2.5 focus:outline-none">
                        <div class="hidden md:block text-right">
                            <p class="text-sm font-semibold text-gray-900 leading-tight">{{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-gray-400">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="size-9 rounded-full bg-[#DB8B00] text-white font-bold flex items-center justify-center">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </button>

                    <div x-show="profileOpen" @click.away="profileOpen = false" x-cloak
                         class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-xs font-bold uppercase text-[#DB8B00]">Admin</p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Dashboard</a>
                        <a href="{{ route('admin.profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Profil Saya</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm font-bold text-red-600 hover:bg-red-50">Keluar</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Inner White Rounded Canvas (Matches Next.js AdminDashboardLayout) --}}
        <main class="flex-1 min-h-0 p-3 sm:p-4 overflow-hidden flex flex-col">
            <div class="flex-1 bg-white border border-gray-100 rounded-[2rem] p-5 sm:p-8 shadow-sm overflow-y-auto no-scrollbar">
                <x-flash-message inline />
                @hasSection('page_title')
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-7 pb-5 border-b border-gray-100">
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

    @livewireScripts
    @stack('scripts')
</body>
</html>
