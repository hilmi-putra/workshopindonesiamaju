<!DOCTYPE html>
<html lang="id" class="h-full antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Workshop Indonesia Maju | Satu Penerbit. Ribuan Cerita Penting.')</title>
    <meta name="description" content="@yield('meta_description', 'Workshop Indonesia Maju - Penerbit buku ajar, buku referensi, novel, jurnal, dan toko buku online resmi WIM Bookstore.')">
    @yield('meta')

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,400..800;1,400..700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-full flex flex-col bg-[#F3F4F7] text-[#111218] font-sans selection:bg-[#2B3288]/15 selection:text-[#2B3288]">
    <x-navbar />

    <x-flash-message />

    <main class="flex-1 w-full relative overflow-x-hidden">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <x-footer />

    <x-floating-whatsapp />

    <livewire:cart-drawer />

    <x-confirm-modal />

    @livewireScripts
    @stack('scripts')
</body>
</html>
