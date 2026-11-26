<!DOCTYPE html>
<html lang="id" class="h-full antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Masuk | Mega Press')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,400..700;1,400..700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen relative flex flex-col items-center justify-center pt-28 pb-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-[#EFEADD] to-[#f4efe5] text-[#171512] font-sans overflow-x-hidden">
    <x-navbar />
    <x-flash-message />

    <div class="fixed inset-0 z-0 pointer-events-none opacity-[0.03]"
         style="background-image: linear-gradient(to right, rgba(0,0,0,0.8) 1px, transparent 1px), linear-gradient(to bottom, rgba(0,0,0,0.8) 1px, transparent 1px); background-size: 4rem 4rem;">
    </div>

    <div class="relative z-10 w-full max-w-md flex-1 flex flex-col justify-center">
        @yield('content')
    </div>

    @livewireScripts
</body>
</html>
