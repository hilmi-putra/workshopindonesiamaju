<!DOCTYPE html>
<html lang="id" class="h-full antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Masuk | Workshop Indonesia Maju')</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,400..700;1,400..700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen relative flex flex-col items-center justify-center pt-28 pb-12 px-4 sm:px-6 lg:px-8 bg-[#F3F4F7] text-[#111218] font-sans overflow-x-hidden">
    <x-navbar />
    <x-flash-message />

    <div class="fixed inset-0 z-0 pointer-events-none wim-editorial-grid opacity-60"></div>

    <div class="relative z-10 w-full max-w-md flex-1 flex flex-col justify-center">
        @yield('content')
    </div>

    @livewireScripts
</body>
</html>
