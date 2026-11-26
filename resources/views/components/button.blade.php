@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
])

@php
    $base = 'inline-flex items-center justify-center font-semibold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#DB8B00]/50 disabled:opacity-50 disabled:pointer-events-none cursor-pointer';
    $variants = [
        'primary' => 'bg-[#DB8B00] hover:bg-[#b06f00] text-white shadow-sm',
        'dark' => 'bg-[#171512] hover:bg-[#DB8B00] text-white',
        'forest' => 'bg-[#084c3c] hover:bg-[#063a2e] text-white',
        'outline' => 'border border-[#DDD8C8] bg-white hover:bg-[#FAF8F4] text-[#171512]',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white',
        'ghost' => 'bg-transparent hover:bg-black/5 text-[#171512]',
    ];
    $sizes = [
        'sm' => 'h-9 px-4 text-xs rounded-lg',
        'md' => 'h-11 px-6 text-sm rounded-xl',
        'lg' => 'h-12 px-8 text-base rounded-full',
        'square' => 'h-11 px-6 text-xs font-bold uppercase tracking-widest rounded-none',
    ];
    $classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
