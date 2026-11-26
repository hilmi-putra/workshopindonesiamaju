@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
])

@php
    $base = 'inline-flex items-center justify-center font-semibold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[var(--brand-primary,#2B3288)]/40 disabled:opacity-50 disabled:pointer-events-none cursor-pointer';
    $variants = [
        'primary' => 'bg-[var(--brand-primary,#2B3288)] hover:bg-[var(--brand-primary-hover,#1F256D)] text-white shadow-sm',
        'dark' => 'bg-[#111218] hover:bg-[var(--brand-primary,#2B3288)] text-white',
        'forest' => 'bg-[#2B3288] hover:bg-[#1F256D] text-white',
        'outline' => 'border border-[var(--brand-border,#C9CEDF)] bg-white hover:bg-[var(--brand-surface,#F3F4F7)] text-[#111218]',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white shadow-sm',
        'ghost' => 'bg-transparent hover:bg-black/5 text-[#111218]',
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
