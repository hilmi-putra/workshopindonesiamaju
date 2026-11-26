<!DOCTYPE html>
<html lang="id" class="h-full antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Mega Press | Satu Penerbit. Ribuan Cerita Penting.')</title>
    <meta name="description" content="@yield('meta_description', 'Mega Press - Penerbit buku ajar, buku referensi, novel, jurnal, dan toko buku online resmi Mega Bookstore.')">
    @yield('meta')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,400..800;1,400..700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-full flex flex-col bg-[#FAF8F4] text-[#171512] font-sans selection:bg-[#DB8B00]/20 selection:text-[#171512]">
    <x-navbar />

    <x-flash-message />

    <main class="flex-1 w-full relative overflow-x-hidden">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <x-footer />

    <x-floating-whatsapp />

    <livewire:cart-drawer />

    @livewireScripts
    @stack('scripts')
</body>
</html>
